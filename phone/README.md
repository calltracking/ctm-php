# CTM Phone Embed PHP Demo

Example PHP Application to connect CallTrackingMetrics embeddable softphone into your application.

  * You can either use CTM's component UI or build your own
  * Authentication is handled automatically via backend connection

## Requirements

* PHP 7.4 or higher
* cURL extension enabled
* Web server (Apache with mod_rewrite enabled or Nginx)

## Configuration

To run the example web application, you will need to ensure the following environment variables are set:

```
CTM_TOKEN: Your API Access key
CTM_SECRET: Your API Secret key
CTM_ACCOUNT_ID: Your CTM Account ID
CTM_HOST: app.calltrackingmetrics.com (optional, defaults to this value)
```

### Setting Environment Variables

#### Apache (.htaccess)
```
SetEnv CTM_SECRET your_actual_secret_here
SetEnv CTM_TOKEN your_actual_token_here
SetEnv CTM_ACCOUNT_ID your_account_id
SetEnv CTM_HOST app.calltrackingmetrics.com
```

#### PHP (php.ini)
```
[PHP]
CTM_SECRET = your_actual_secret_here
CTM_TOKEN = your_actual_token_here
CTM_ACCOUNT_ID = your_account_id
CTM_HOST = app.calltrackingmetrics.com
```

#### Command Line
```bash
# Linux/Mac
export CTM_SECRET=your_actual_secret_here
export CTM_TOKEN=your_actual_token_here
export CTM_ACCOUNT_ID=your_account_id
export CTM_HOST=app.calltrackingmetrics.com

# Windows Command Prompt
set CTM_SECRET=your_actual_secret_here
set CTM_TOKEN=your_actual_token_here
set CTM_ACCOUNT_ID=your_account_id
set CTM_HOST=app.calltrackingmetrics.com

# Windows PowerShell
$env:CTM_SECRET="your_actual_secret_here"
$env:CTM_TOKEN="your_actual_token_here"
$env:CTM_ACCOUNT_ID="your_account_id"
$env:CTM_HOST="app.calltrackingmetrics.com"
```

#### Using .env file (with php-dotenv library)
Create a `.env` file in the root directory:
```
CTM_SECRET=your_actual_secret_here
CTM_TOKEN=your_actual_token_here
CTM_ACCOUNT_ID=your_account_id
CTM_HOST=app.calltrackingmetrics.com
```

## Installation

1. Clone or download this repository
2. Set the environment variables using one of the methods above
3. Place the files in your web server's document root
4. Ensure the web server has permission to read the files
5. Visit the application in your web browser

## API Documentation

### CTM Access API
To enable single sign-on with CTM, the application sends a request to the CTM Access API to get a token. This token is used to authenticate the user with the CTM API. The token is valid for 5 minutes and can be used to authenticate the user with the CTM API.

### Endpoints

- `/`: Main application page with the phone embed
- `/ctm-phone-access`: POST endpoint to get a token from CTM
- `/ctm-device`: Device embedding page for the popout functionality

## Troubleshooting

- Check that all environment variables are set correctly
- Ensure the web server has permission to read the files
- Check the PHP error log for any errors
- Verify cURL is enabled in your PHP installation
