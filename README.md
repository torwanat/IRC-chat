# IRC Chat

A lightweight, real-time chat application built with PHP and JavaScript. Users can instantly communicate with anonymous nicknames, customize their message colors, and use emoji reactions. Perfect for simple web-based communication without user authentication.

## Features

- **Real-time messaging** – Messages appear instantly using long polling
- **Anonymous chat** – No registration required; choose a random or custom nickname
- **Color-coded users** – Each user gets a unique color for easy identification
- **Emoji support** – CSS emoticons for expressive messaging
- **Simple commands** – Manage your nickname and message color on the fly
- **Lightweight architecture** – Uses Memcached for efficient message storage

## Getting Started

### Prerequisites

- **PHP 5.4+** – Server-side chat logic
- **Memcached server** – Running on `localhost:11211` for message storage
- **Modern web browser** – Chrome, Firefox, Safari, or Edge

### Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd IRC-chat
   ```

2. **Set up Memcached**
   - Install Memcached on your system
   - Start the Memcached server (default: `localhost:11211`)
   - On Windows: Download from [Memcached Windows](https://memcached.org/)
   - On Linux: `sudo apt-get install memcached` and start with `memcached -d`
   - On macOS: `brew install memcached` and start with `memcached -d`

3. **Configure PHP server**
   - Place the project directory in your web server's document root
   - Or run the built-in PHP server:
     ```bash
     php -S localhost:8000
     ```

4. **Open in browser**
   - Navigate to `http://localhost:8000`
   - Enter a nickname (or leave blank for a random one)
   - Start chatting!

### Usage

#### Basic Chat
- Type a message and press **Enter** to send
- Messages appear instantly with timestamp, nickname, and color

#### Commands

| Command | Description | Example |
|---------|-------------|---------|
| `/nick <name>` | Change your nickname | `/nick Alice` |
| `/color <hex>` | Set your message color | `/color #FF0000` |
| `/quit` | Disconnect and refresh | `/quit` |

#### Color Codes
Use hex color codes to customize your message appearance:
- `#FF0000` – Red
- `#00FF00` – Green
- `#0000FF` – Blue
- `#FFFF00` – Yellow

## Project Structure

```
IRC-chat/
├── index.html          # Main chat interface
├── index.js            # Client-side chat logic
├── index.css           # UI styling
├── send.php            # Handles message sending and commands
├── alp.php             # Long polling endpoint for receiving messages
├── add.php             # Additional functionality
├── test.php            # Testing utilities
├── javascripts/        # jQuery and emoticon libraries
│   ├── jquery-1.4.2.min.js
│   └── jquery.cssemoticons.min.js
├── stylesheets/        # Emoticon CSS
│   └── jquery.cssemoticons.css
├── fakescroll/         # Custom scrollbar implementation
│   ├── fakescroll.js
│   └── fakescroll.css
└── memcached/          # Memcached configuration
```

## How It Works

1. **Frontend** – User enters a nickname and joins the chat room
2. **Message Sending** – Message is sent to `send.php` via POST request
3. **Storage** – Messages are stored in Memcached with a unique ID
4. **Retrieval** – Client polls `alp.php` every 5 seconds to fetch new messages
5. **Display** – New messages appear in real-time with emoji rendering

### Areas for Enhancement

- User authentication and persistent profiles
- Message history storage in database
- Private messaging between users
- Moderation and admin commands
- Mobile-responsive design improvements
- WebSocket implementation for true real-time updates
