# AI Blog Content Generator

A powerful WordPress plugin that integrates AI-powered content generation directly into the Gutenberg editor. Generate high-quality blog posts, articles, product descriptions, and more using multiple AI providers including **FREE options**!

## 🆓 Free AI Services Supported

This plugin now supports multiple AI providers, including completely free options:

### 1. **Hugging Face (Recommended for Free)**
- ✅ **Completely FREE** - No API key required!
- ✅ Good quality content generation
- ✅ No rate limits for basic usage
- ✅ Easy setup - just select from dropdown

### 2. **Google Gemini (Best Free Quality)**
- ✅ **FREE tier** with 15 requests per minute
- ✅ Excellent content quality
- ✅ Simple API key setup
- ✅ Great for most use cases

### 3. **Ollama (Local - Completely Free)**
- ✅ **100% FREE** - Runs on your own server
- ✅ No internet required after setup
- ✅ Complete privacy
- ✅ Requires local installation

### 4. **OpenAI (Paid - Best Quality)**
- 💰 Pay-per-use pricing
- 🏆 Highest quality content
- ⚡ Fastest response times
- 🔧 Most reliable service

## Features

- 🤖 **Multiple AI Providers**: Choose from OpenAI, Hugging Face, Google Gemini, or Ollama
- 🆓 **Free Options**: Use Hugging Face without any API key!
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
- AI provider API key (optional for Hugging Face)

## Installation

### Method 1: Manual Installation

1. Download the plugin files
2. Upload the `ai-blog-content-generator` folder to your `/wp-content/plugins/` directory
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Navigate to Settings > AI Content Generator to configure your AI provider

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

### 🆓 Quick Start with Free AI

1. **Go to Settings > AI Content Generator**
2. **Select "Hugging Face" from AI Provider dropdown**
3. **Leave API key empty (works without it!)**
4. **Save settings and start generating content!**

### Detailed Setup by Provider

#### 1. Hugging Face (FREE - No Setup Required)
1. Select "Hugging Face" from AI Provider dropdown
2. Leave API key field empty
3. Save settings
4. Start generating content immediately!

#### 2. Google Gemini (FREE Tier)
1. Go to [Google AI Studio](https://makersuite.google.com/app/apikey)
2. Create a free API key
3. Select "Google" from AI Provider dropdown
4. Enter your API key and save
5. Free tier includes 15 requests per minute

#### 3. Ollama (Local - Completely Free)
1. Install Ollama from [ollama.ai](https://ollama.ai)
2. Run `ollama run llama2` in terminal
3. Select "Ollama" from AI Provider dropdown
4. Keep default URL (http://localhost:11434)
5. Start generating content locally

#### 4. OpenAI (Paid)
1. Get an OpenAI API key from [OpenAI Platform](https://platform.openai.com/api-keys)
2. Select "OpenAI" from AI Provider dropdown
3. Enter your API key
4. Choose your preferred model (GPT-3.5 Turbo is most cost-effective)

## Usage

1. **Configure your AI provider** in Settings > AI Content Generator
2. **Create content templates** for quick access (optional)
3. **In the Gutenberg editor**, add the "AI Content Generator" block
4. **Enter your prompt** or select a template
5. **Choose content type, tone, and length**
6. **Click "Generate Content"** and wait for the AI response
7. **Review and edit** the generated content as needed

## Cost Comparison

| Provider | Cost | Quality | Setup Difficulty |
|----------|------|---------|------------------|
| **Hugging Face** | 🆓 **FREE** | Good | ⭐ Easy |
| **Google Gemini** | 🆓 **FREE** (15 req/min) | Excellent | ⭐ Easy |
| **Ollama** | 🆓 **FREE** | Good | ⭐⭐ Medium |
| **OpenAI GPT-3.5** | 💰 ~$0.002/1K tokens | Very Good | ⭐ Easy |
| **OpenAI GPT-4** | 💰 ~$0.03/1K tokens | Best | ⭐ Easy |

## Troubleshooting

### Common Issues

1. **"Quota exceeded" Error**
   - Switch to Hugging Face (works without API key)
   - Or use Google Gemini free tier

2. **"Invalid API key" Error**
   - Check that you copied the API key correctly
   - Make sure you're using the right provider

3. **Block not appearing**
   - Ensure the plugin is activated
   - Refresh the page

4. **Ollama not working**
   - Make sure Ollama is installed and running
   - Check that `ollama run llama2` is running in terminal

## Support

For support and feature requests:

1. Check the troubleshooting section in the admin panel
2. Review the WordPress error logs
3. Test with a default WordPress theme
4. Disable other plugins to check for conflicts

## Changelog

### Version 1.1.0
- Added support for multiple AI providers
- Added Hugging Face (FREE - no API key required)
- Added Google Gemini (FREE tier)
- Added Ollama (local - completely free)
- Improved error handling and user experience
- Added comprehensive troubleshooting guide

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
- Powered by multiple AI providers
- Uses WordPress Scripts for build process

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

---

**Note**: This plugin now supports multiple AI providers including completely free options. You can start using it immediately with Hugging Face without any API key or cost! 