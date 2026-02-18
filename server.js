const express = require('express');
const multer = require('multer');
const fs = require('fs');
const path = require('path');

const app = express();
const port = 8080;
const uploadDir = path.join(__dirname, 'uploads');

// Configure how files are saved
const storage = multer.diskStorage({
    destination: (req, file, cb) => cb(null, uploadDir),
    filename: (req, file, cb) => cb(null, file.originalname)
});
const upload = multer({ storage });

// Serve static HTML file
app.use(express.static('.'));

// API: List files
app.get('/api/files', (req, res) => {
    fs.readdir(uploadDir, (err, files) => {
        if (err) return res.status(500).json([]);
        res.json(files);
    });
});

// API: Upload
app.post('/api/upload', upload.single('file'), (req, res) => res.sendStatus(200));

// API: Download
app.get('/api/download/:name', (req, res) => {
    res.download(path.join(uploadDir, req.params.name));
});

// API: Delete
app.delete('/api/delete/:name', (req, res) => {
    fs.unlink(path.join(uploadDir, req.params.name), () => res.sendStatus(200));
});

app.listen(port, () => console.log(`SentFile running at http://localhost:${port}`));
