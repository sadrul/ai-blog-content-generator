# AI Blog Content Generator

A powerful WordPress plugin that integrates AI-powered content generation directly into the Gutenberg editor. Generate high-quality blog posts, articles, product descriptions, and more using OpenAI's advanced language models.

## Features

- 🤖 **AI-Powered Content Generation**: Generate content using OpenAI's GPT models
- 📝 **Gutenberg Integration**: Seamless integration with WordPress block editor
- 🎨 **Multiple Content Types**: Blog posts, articles, product descriptions, social media content, emails
- 🎭 **Tone Control**: Choose from professional, casual, friendly, formal, conversational, or enthusiastic tones
- 📏 **Length Control**: Specify target word count for generated content
- 📋 **Template System**: Create and save reusable content templates
- ⚙️ **Admin Settings**: Comprehensive configuration panel
- 🔒 **Security**: Secure API key management and nonce verification
- 📱 **Responsive Design**: Works perfectly on all devices

## Requirements

- WordPress 5.0 or higher
- PHP 7.4 or higher
- OpenAI API key
- Node.js and npm (for development)

## Installation

### Method 1: Manual Installation

1. Download the plugin files
2. Upload the `ai-blog-content-generator` folder to your `/wp-content/plugins/` directory
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Navigate to Settings > AI Content Generator to configure your API key

### Method 2: Development Installation

1. Clone or download the plugin files
2. Navigate to the plugin directory in your terminal
3. Install dependencies:
   ```bash
   npm install
   ```
4. Build the plugin:
   ```bash
   npm run build
   ```
5. Activate the plugin in WordPress admin

## Configuration

### 1. API Setup

1. Get an OpenAI API key from [OpenAI Platform](https://platform.openai.com/api-keys)
2. Go to WordPress Admin > Settings > AI Content Generator
3. Enter your API key in the "OpenAI API Key" field
4. Choose your preferred AI model:
   - **GPT-3.5 Turbo**: Fast and cost-effective
   - **GPT-4**: High quality content
   - **GPT-4 Turbo**: Latest and recommended

### 2. Advanced Settings

- **Max Tokens**: Control the maximum length of generated content (100-4000)
- **Creativity Level**: Adjust the temperature setting (0-2.0)
  - Lower values (0-0.5): More focused and consistent
  - Higher values (0.7-2.0): More creative and varied

### 3. Content Templates

Create reusable templates for quick content generation:

1. Go to Settings > AI Content Generator
2. Scroll to the "Content Templates" section
3. Fill in the template details:
   - Template Name
   - Default Prompt
   - Content Type
   - Default Tone
   - Default Length
4. Click "Save Template"

## Usage

### Using the Gutenberg Block

1. **Add the Block**:
   - Open the Gutenberg editor
   - Click the "+" button to add a new block
   - Search for "AI Content Generator"
   - Select the block

2. **Generate Content**:
   - Enter your content prompt
   - (Optional) Click "Show Advanced" to customize settings
   - Choose content type, tone, and length
   - Click "Generate Content"
   - Wait for the AI to generate your content

3. **Use Templates**:
   - Select a saved template from the dropdown
   - Modify the prompt if needed
   - Generate content with template settings

4. **Insert Content**:
   - Review the generated content
   - Click "Insert into Editor" to add it to your post
   - Edit the content as needed

### Content Types

- **Blog Post**: Comprehensive blog posts with headings and structure
- **Article**: Detailed articles with facts and insights
- **Product Description**: Compelling product descriptions
- **Social Media**: Engaging social media content
- **Email**: Professional email content

### Tone Options

- **Professional**: Formal and business-like
- **Casual**: Relaxed and informal
- **Friendly**: Warm and approachable
- **Formal**: Very structured and official
- **Conversational**: Natural and chatty
- **Enthusiastic**: Energetic and excited

## Development

### Building the Plugin

```bash
# Install dependencies
npm install

# Start development mode (with hot reloading)
npm start

# Build for production
npm run build

# Run linting
npm run lint:js
npm run lint:css
```

### File Structure

```
ai-blog-content-generator/
├── admin/
│   └── admin-page.php          # Admin settings page
├── src/
│   ├── index.js               # Main block JavaScript
│   └── style.scss             # Block styles
├── build/                     # Built files (generated)
├── ai-blog-content-generator.php  # Main plugin file
├── package.json               # Dependencies and scripts
├── webpack.config.js          # Build configuration
└── README.md                  # This file
```

### Customization

#### Adding New Content Types

1. Edit `src/index.js` and add your content type to the options array
2. Update the `build_prompt()` method in the main PHP file
3. Add corresponding template options

#### Styling

Modify `src/style.scss` to customize the block appearance. The styles are compiled to CSS during the build process.

## Security

- API keys are stored securely in WordPress options
- All AJAX requests are protected with nonces
- Input is properly sanitized and validated
- User permissions are checked for all operations

## Troubleshooting

### Common Issues

1. **"OpenAI API key not configured"**
   - Go to Settings > AI Content Generator
   - Enter your valid OpenAI API key

2. **"Network error"**
   - Check your internet connection
   - Verify your API key is correct
   - Check if OpenAI services are available

3. **"Invalid response from AI service"**
   - Your API key might be invalid or expired
   - Check your OpenAI account for usage limits
   - Verify the selected model is available

4. **Block not appearing**
   - Ensure the plugin is activated
   - Check browser console for JavaScript errors
   - Verify the build files exist in the `/build/` directory

### Debug Mode

Enable WordPress debug mode to see detailed error messages:

```php
// Add to wp-config.php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
```

## Support

For support and feature requests:

1. Check the troubleshooting section above
2. Review the WordPress error logs
3. Test with a default WordPress theme
4. Disable other plugins to check for conflicts

## Changelog

### Version 1.0.0
- Initial release
- Gutenberg block integration
- OpenAI API integration
- Template system
- Admin settings panel
- Multiple content types and tones

## License

This plugin is licensed under the GPL v2 or later.

## Credits

- Author: K M Sadrul Ula
- Author URI: https://github.com/sadrul
- Author Email: kmsadrulula@gmail.com
- Built with WordPress and React
- Powered by OpenAI GPT models
- Uses WordPress Scripts for build process

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

---

**Note**: This plugin requires an OpenAI API key and will incur costs based on your usage. Please review OpenAI's pricing before use. 