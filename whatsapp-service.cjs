const { Client, LocalAuth } = require('whatsapp-web.js');
const qrcode = require('qrcode');
const express = require('express');
const cors = require('cors');
const fs = require('fs');
const path = require('path');

const app = express();
app.use(cors());
app.use(express.json());

const PORT = 3001;
const STATUS_FILE = path.join(__dirname, 'storage', 'whatsapp-status.json');

let client = null;
let qrCodeData = null;
let isReady = false;
let statusMessage = 'Initializing...';

// Ensure storage directory exists
if (!fs.existsSync(path.join(__dirname, 'storage'))) {
    fs.mkdirSync(path.join(__dirname, 'storage'), { recursive: true });
}

function saveStatus() {
    const status = {
        isReady,
        statusMessage,
        qrCode: qrCodeData,
        updatedAt: new Date().toISOString()
    };
    fs.writeFileSync(STATUS_FILE, JSON.stringify(status, null, 2));
}

function initWhatsApp() {
    console.log('Initializing WhatsApp client...');
    statusMessage = 'Initializing WhatsApp...';
    saveStatus();

    client = new Client({
        authStrategy: new LocalAuth({
            dataPath: path.join(__dirname, '.wwebjs_auth')
        }),
        puppeteer: {
            headless: true,
            executablePath: 'C:\\Program Files\\Google\\Chrome\\Application\\chrome.exe',
            args: [
                '--no-sandbox',
                '--disable-setuid-sandbox',
                '--disable-dev-shm-usage',
                '--disable-accelerated-2d-canvas',
                '--no-first-run',
                '--no-zygote',
                '--disable-gpu'
            ]
        }
    });

    client.on('qr', async (qr) => {
        console.log('QR Code received');
        qrCodeData = await qrcode.toDataURL(qr);
        statusMessage = 'Scan QR Code dengan WhatsApp';
        isReady = false;
        saveStatus();
    });

    client.on('ready', () => {
        console.log('WhatsApp client is ready!');
        qrCodeData = null;
        isReady = true;
        statusMessage = 'WhatsApp Connected!';
        saveStatus();
    });

    client.on('authenticated', () => {
        console.log('WhatsApp authenticated');
        statusMessage = 'Authenticated, loading...';
        saveStatus();
    });

    client.on('auth_failure', (msg) => {
        console.error('Authentication failed:', msg);
        statusMessage = 'Authentication failed: ' + msg;
        isReady = false;
        saveStatus();
    });

    client.on('disconnected', (reason) => {
        console.log('WhatsApp disconnected:', reason);
        statusMessage = 'Disconnected: ' + reason;
        isReady = false;
        qrCodeData = null;
        saveStatus();
        
        // Reinitialize after disconnect
        setTimeout(() => {
            initWhatsApp();
        }, 5000);
    });

    client.initialize();
}

// API Endpoints
app.get('/status', (req, res) => {
    res.json({
        isReady,
        statusMessage,
        qrCode: qrCodeData
    });
});

app.post('/send', async (req, res) => {
    const { phone, message } = req.body;

    if (!isReady) {
        return res.status(400).json({ 
            success: false, 
            error: 'WhatsApp not connected' 
        });
    }

    if (!phone || !message) {
        return res.status(400).json({ 
            success: false, 
            error: 'Phone and message required' 
        });
    }

    try {
        // Format phone number
        let formattedPhone = phone.toString().replace(/\D/g, '');
        if (formattedPhone.startsWith('0')) {
            formattedPhone = '62' + formattedPhone.substring(1);
        }
        formattedPhone = formattedPhone + '@c.us';

        await client.sendMessage(formattedPhone, message);
        console.log('Message sent to:', phone);
        
        res.json({ 
            success: true, 
            message: 'Message sent successfully' 
        });
    } catch (error) {
        console.error('Send error:', error);
        res.status(500).json({ 
            success: false, 
            error: error.message 
        });
    }
});

app.post('/logout', async (req, res) => {
    try {
        if (client) {
            await client.logout();
            await client.destroy();
        }
        isReady = false;
        qrCodeData = null;
        statusMessage = 'Logged out';
        saveStatus();
        
        // Reinitialize
        setTimeout(() => {
            initWhatsApp();
        }, 2000);
        
        res.json({ success: true, message: 'Logged out successfully' });
    } catch (error) {
        res.status(500).json({ success: false, error: error.message });
    }
});

app.post('/restart', async (req, res) => {
    try {
        if (client) {
            await client.destroy();
        }
        isReady = false;
        qrCodeData = null;
        statusMessage = 'Restarting...';
        saveStatus();
        
        setTimeout(() => {
            initWhatsApp();
        }, 2000);
        
        res.json({ success: true, message: 'Restarting...' });
    } catch (error) {
        res.status(500).json({ success: false, error: error.message });
    }
});

// Start server
app.listen(PORT, () => {
    console.log(`WhatsApp service running on port ${PORT}`);
    initWhatsApp();
});
