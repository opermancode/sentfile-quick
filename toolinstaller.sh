#!/bin/bash

set -e

# Detect package manager
if command -v apt >/dev/null; then
    PM="apt"
elif command -v yum >/dev/null; then
    PM="yum"
elif command -v dnf >/dev/null; then
    PM="dnf"
elif command -v pacman >/dev/null; then
    PM="pacman"
else
    echo "Unsupported package manager. Install manually."
    exit 1
fi

echo "Detected package manager: $PM"

# Update system
case "$PM" in
    apt)
        sudo apt update -y
        sudo apt install -y curl git apache2
        # Install Node.js (LTS)
        curl -fsSL https://deb.nodesource.com/setup_lts.x | sudo -E bash -
        sudo apt install -y nodejs
        ;;
    yum)
        sudo yum install -y curl git httpd
        curl -fsSL https://rpm.nodesource.com/setup_lts.x | sudo bash -
        sudo yum install -y nodejs
        ;;
    dnf)
        sudo dnf install -y curl git httpd
        curl -fsSL https://rpm.nodesource.com/setup_lts.x | sudo bash -
        sudo dnf install -y nodejs
        ;;
    pacman)
        sudo pacman -Sy --noconfirm curl git apache nodejs npm
        ;;
esac

# Enable and start Apache service
if systemctl list-unit-files | grep -q apache2.service; then
    sudo systemctl enable apache2
    sudo systemctl start apache2
elif systemctl list-unit-files | grep -q httpd.service; then
    sudo systemctl enable httpd
    sudo systemctl start httpd
fi

echo "✅ Installation complete: Node.js, Git, and Apache installed."
echo "Node.js version: $(node -v)"
echo "Git version: $(git --version)"
