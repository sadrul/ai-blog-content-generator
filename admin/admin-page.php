<?php
/**
 * Admin settings page for AI Blog Content Generator.
 *
 * @package AI_Blog_Content_Generator
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="wrap">
	<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
	
	<!-- Important Notice Section -->
	<div class="notice notice-info">
		<h3><?php _e( '⚠️ Important: ChatGPT Plus vs OpenAI API', 'ai-blog-content-generator' ); ?></h3>
		<p><strong><?php _e( 'ChatGPT Plus subscription and OpenAI API are separate services:', 'ai-blog-content-generator' ); ?></strong></p>
		<ul style="margin-left: 20px;">
			<li><?php _e( '• <strong>ChatGPT Plus</strong> ($20/month): Access to ChatGPT web interface only', 'ai-blog-content-generator' ); ?></li>
			<li><?php _e( '• <strong>OpenAI API</strong>: Separate service for developers with its own billing', 'ai-blog-content-generator' ); ?></li>
		</ul>
		<p><strong><?php _e( 'To use this plugin, you need an OpenAI API key:', 'ai-blog-content-generator' ); ?></strong></p>
		<ol style="margin-left: 20px;">
			<li><?php _e( 'Go to <a href="https://platform.openai.com/signup" target="_blank">OpenAI Platform</a> and create a new account', 'ai-blog-content-generator' ); ?></li>
			<li><?php _e( 'Add a payment method (credit card required)', 'ai-blog-content-generator' ); ?></li>
			<li><?php _e( 'Create an API key at <a href="https://platform.openai.com/api-keys" target="_blank">API Keys page</a>', 'ai-blog-content-generator' ); ?></li>
			<li><?php _e( 'Enter the API key below', 'ai-blog-content-generator' ); ?></li>
		</ol>
		<p><strong><?php _e( 'Costs:', 'ai-blog-content-generator' ); ?></strong> <?php _e( 'API usage is charged per token. GPT-3.5 Turbo costs ~$0.002 per 1K tokens (~750 words).', 'ai-blog-content-generator' ); ?></p>
	</div>
	
	<div class="aibcg-admin-container">
		<div class="aibcg-admin-content">
			<!-- API Settings Section -->
			<div class="aibcg-section">
				<h2><?php _e( 'AI Provider Configuration', 'ai-blog-content-generator' ); ?></h2>
				<p><?php _e( 'Choose your preferred AI service. Some options are completely free!', 'ai-blog-content-generator' ); ?></p>
				
				<form method="post" action="options.php">
					<?php settings_fields( 'aibcg_settings' ); ?>
					
					<table class="form-table">
						<tr>
							<th scope="row">
								<label for="aibcg_ai_provider"><?php _e( 'AI Provider', 'ai-blog-content-generator' ); ?></label>
							</th>
							<td>
								<select id="aibcg_ai_provider" name="aibcg_ai_provider">
									<option value="huggingface" <?php selected( get_option( 'aibcg_ai_provider', 'huggingface' ), 'huggingface' ); ?>>
										🆓 Hugging Face (FREE - Good Quality)
									</option>
									<option value="google" <?php selected( get_option( 'aibcg_ai_provider', 'huggingface' ), 'google' ); ?>>
										🆓 Google Gemini (FREE Tier - Excellent)
									</option>
									<option value="openai" <?php selected( get_option( 'aibcg_ai_provider', 'huggingface' ), 'openai' ); ?>>
										🤖 OpenAI GPT (Paid - Best Quality)
									</option>
									<option value="ollama" <?php selected( get_option( 'aibcg_ai_provider', 'huggingface' ), 'ollama' ); ?>>
										🆓 Ollama (FREE - Local Installation)
									</option>
								</select>
								<p class="description">
									<?php _e( 'Choose your AI provider. Free options are marked with 🆓. Hugging Face works without API key!', 'ai-blog-content-generator' ); ?>
								</p>
							</td>
						</tr>
						
						<!-- Hugging Face Settings -->
						<tr id="huggingface-settings" class="provider-settings">
							<th scope="row">
								<label for="aibcg_huggingface_api_key"><?php _e( 'Hugging Face API Key (Optional)', 'ai-blog-content-generator' ); ?></label>
							</th>
							<td>
								<input type="password" 
									   id="aibcg_huggingface_api_key" 
									   name="aibcg_huggingface_api_key" 
									   value="<?php echo esc_attr( get_option( 'aibcg_huggingface_api_key' ) ); ?>" 
									   class="regular-text" />
								<p class="description">
									<?php _e( 'Hugging Face API key is optional - the service works without it! Get one from', 'ai-blog-content-generator' ); ?> 
									<a href="https://huggingface.co/settings/tokens" target="_blank"><?php _e( 'Hugging Face Settings', 'ai-blog-content-generator' ); ?></a>
									<?php _e( ' for higher rate limits.', 'ai-blog-content-generator' ); ?>
								</p>
							</td>
						</tr>
						
						<!-- Google Settings -->
						<tr id="google-settings" class="provider-settings" style="display: none;">
							<th scope="row">
								<label for="aibcg_google_api_key"><?php _e( 'Google API Key', 'ai-blog-content-generator' ); ?></label>
							</th>
							<td>
								<input type="password" 
									   id="aibcg_google_api_key" 
									   name="aibcg_google_api_key" 
									   value="<?php echo esc_attr( get_option( 'aibcg_google_api_key' ) ); ?>" 
									   class="regular-text" />
								<p class="description">
									<?php _e( 'Get your Google API key from', 'ai-blog-content-generator' ); ?> 
									<a href="https://makersuite.google.com/app/apikey" target="_blank"><?php _e( 'Google AI Studio', 'ai-blog-content-generator' ); ?></a>
									<?php _e( '. Free tier includes 15 requests per minute.', 'ai-blog-content-generator' ); ?>
								</p>
							</td>
						</tr>
						
						<!-- OpenAI Settings -->
						<tr id="openai-settings" class="provider-settings" style="display: none;">
							<th scope="row">
								<label for="aibcg_openai_api_key"><?php _e( 'OpenAI API Key', 'ai-blog-content-generator' ); ?></label>
							</th>
							<td>
								<input type="password" 
									   id="aibcg_openai_api_key" 
									   name="aibcg_openai_api_key" 
									   value="<?php echo esc_attr( get_option( 'aibcg_openai_api_key' ) ); ?>" 
									   class="regular-text" />
								<p class="description">
									<?php _e( 'Enter your OpenAI API key. You can get one from', 'ai-blog-content-generator' ); ?> 
									<a href="https://platform.openai.com/api-keys" target="_blank"><?php _e( 'OpenAI Platform', 'ai-blog-content-generator' ); ?></a>
									<?php _e( '. <strong>Note:</strong> This is different from ChatGPT Plus - you need a separate OpenAI API account with billing setup.', 'ai-blog-content-generator' ); ?>
								</p>
							</td>
						</tr>
						
						<!-- Ollama Settings -->
						<tr id="ollama-settings" class="provider-settings" style="display: none;">
							<th scope="row">
								<label for="aibcg_ollama_url"><?php _e( 'Ollama URL', 'ai-blog-content-generator' ); ?></label>
							</th>
							<td>
								<input type="url" 
									   id="aibcg_ollama_url" 
									   name="aibcg_ollama_url" 
									   value="<?php echo esc_attr( get_option( 'aibcg_ollama_url', 'http://localhost:11434' ) ); ?>" 
									   class="regular-text" />
								<p class="description">
									<?php _e( 'Ollama runs locally on your server. Install from', 'ai-blog-content-generator' ); ?> 
									<a href="https://ollama.ai" target="_blank"><?php _e( 'ollama.ai', 'ai-blog-content-generator' ); ?></a>
									<?php _e( ' and run: <code>ollama run llama2</code>', 'ai-blog-content-generator' ); ?>
								</p>
							</td>
						</tr>
						
						<tr>
							<th scope="row">
								<label for="aibcg_default_model"><?php _e( 'Default Model', 'ai-blog-content-generator' ); ?></label>
							</th>
							<td>
								<select id="aibcg_default_model" name="aibcg_default_model">
									<option value="microsoft/DialoGPT-medium">DialoGPT Medium (Good Quality)</option>
									<option value="gpt2">GPT-2 (Fast)</option>
									<option value="EleutherAI/gpt-neo-125M">GPT-Neo 125M (Lightweight)</option>
								</select>
								<p class="description">
									<?php _e( 'Choose the AI model for content generation. The available models will update when you change the AI provider above.', 'ai-blog-content-generator' ); ?>
								</p>
							</td>
						</tr>
						
						<tr>
							<th scope="row">
								<label for="aibcg_max_tokens"><?php _e( 'Max Tokens', 'ai-blog-content-generator' ); ?></label>
							</th>
							<td>
								<input type="number" 
									   id="aibcg_max_tokens" 
									   name="aibcg_max_tokens" 
									   value="<?php echo esc_attr( get_option( 'aibcg_max_tokens', 1000 ) ); ?>" 
									   min="100" 
									   max="4000" 
									   class="small-text" />
								<p class="description">
									<?php _e( 'Maximum number of tokens for generated content (100-4000). Higher values allow longer content but cost more.', 'ai-blog-content-generator' ); ?>
								</p>
							</td>
						</tr>
						
						<tr>
							<th scope="row">
								<label for="aibcg_temperature"><?php _e( 'Creativity Level', 'ai-blog-content-generator' ); ?></label>
							</th>
							<td>
								<input type="range" 
									   id="aibcg_temperature" 
									   name="aibcg_temperature" 
									   value="<?php echo esc_attr( get_option( 'aibcg_temperature', 0.7 ) ); ?>" 
									   min="0" 
									   max="2" 
									   step="0.1" 
									   class="regular-text" />
								<span id="temperature-value"><?php echo esc_html( get_option( 'aibcg_temperature', 0.7 ) ); ?></span>
								<p class="description">
									<?php _e( 'Controls creativity vs consistency. Lower values (0-0.5) are more focused, higher values (0.7-2.0) are more creative.', 'ai-blog-content-generator' ); ?>
								</p>
							</td>
						</tr>
					</table>
					
					<?php submit_button( __( 'Save API Settings', 'ai-blog-content-generator' ) ); ?>
				</form>
			</div>
			
			<!-- Troubleshooting Section -->
			<div class="aibcg-section">
				<h2><?php _e( 'Troubleshooting', 'ai-blog-content-generator' ); ?></h2>
				<div class="aibcg-troubleshooting">
					<h3><?php _e( 'Common Issues:', 'ai-blog-content-generator' ); ?></h3>
					<div class="aibcg-issue">
						<h4><?php _e( '❌ "You exceeded your current quota" Error', 'ai-blog-content-generator' ); ?></h4>
						<p><strong><?php _e( 'Solution:', 'ai-blog-content-generator' ); ?></strong> <?php _e( 'This means you need a separate OpenAI API account. ChatGPT Plus subscription does not include API access.', 'ai-blog-content-generator' ); ?></p>
						<p><strong><?php _e( 'FREE Alternative:', 'ai-blog-content-generator' ); ?></strong> <?php _e( 'Switch to Hugging Face (works without API key) or Google Gemini (free tier) in the AI Provider dropdown above!', 'ai-blog-content-generator' ); ?></p>
					</div>
					
					<div class="aibcg-issue">
						<h4><?php _e( '🆓 How to Use Free AI Services', 'ai-blog-content-generator' ); ?></h4>
						<p><strong><?php _e( 'Hugging Face (Recommended for Free):', 'ai-blog-content-generator' ); ?></strong></p>
						<ol>
							<li><?php _e( 'Select "Hugging Face" from AI Provider dropdown', 'ai-blog-content-generator' ); ?></li>
							<li><?php _e( 'Leave API key empty (works without it!)', 'ai-blog-content-generator' ); ?></li>
							<li><?php _e( 'Save settings and start generating', 'ai-blog-content-generator' ); ?></li>
						</ol>
						
						<p><strong><?php _e( 'Google Gemini (Best Free Quality):', 'ai-blog-content-generator' ); ?></strong></p>
						<ol>
							<li><?php _e( 'Go to <a href="https://makersuite.google.com/app/apikey" target="_blank">Google AI Studio</a>', 'ai-blog-content-generator' ); ?></li>
							<li><?php _e( 'Create a free API key', 'ai-blog-content-generator' ); ?></li>
							<li><?php _e( 'Select "Google" from AI Provider dropdown', 'ai-blog-content-generator' ); ?></li>
							<li><?php _e( 'Enter your API key and save', 'ai-blog-content-generator' ); ?></li>
						</ol>
						
						<p><strong><?php _e( 'Ollama (Local Installation):', 'ai-blog-content-generator' ); ?></strong></p>
						<ol>
							<li><?php _e( 'Install Ollama from <a href="https://ollama.ai" target="_blank">ollama.ai</a>', 'ai-blog-content-generator' ); ?></li>
							<li><?php _e( 'Open terminal and run: <code>ollama run llama2</code>', 'ai-blog-content-generator' ); ?></li>
							<li><?php _e( 'Select "Ollama" from AI Provider dropdown', 'ai-blog-content-generator' ); ?></li>
							<li><?php _e( 'Verify URL is <code>http://localhost:11434</code>', 'ai-blog-content-generator' ); ?></li>
							<li><?php _e( 'Save settings and test generation', 'ai-blog-content-generator' ); ?></li>
						</ol>
					</div>
					
					<div class="aibcg-issue">
						<h4><?php _e( '💰 Cost Comparison', 'ai-blog-content-generator' ); ?></h4>
						<p><strong><?php _e( 'Pricing:', 'ai-blog-content-generator' ); ?></strong></p>
						<ul>
							<li><?php _e( '🆓 <strong>Hugging Face:</strong> Completely FREE (no API key needed)', 'ai-blog-content-generator' ); ?></li>
							<li><?php _e( '🆓 <strong>Google Gemini:</strong> FREE tier (15 requests/minute)', 'ai-blog-content-generator' ); ?></li>
							<li><?php _e( '🆓 <strong>Ollama:</strong> Completely FREE (runs locally)', 'ai-blog-content-generator' ); ?></li>
							<li><?php _e( '💰 <strong>OpenAI GPT-3.5:</strong> ~$0.002 per 1K tokens (~750 words)', 'ai-blog-content-generator' ); ?></li>
							<li><?php _e( '💰 <strong>OpenAI GPT-4:</strong> ~$0.03 per 1K tokens (~750 words)', 'ai-blog-content-generator' ); ?></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<style>
.aibcg-admin-container {
	max-width: 1200px;
}

.aibcg-section {
	background: #fff;
	border: 1px solid #ccd0d4;
	border-radius: 4px;
	padding: 20px;
	margin-bottom: 20px;
}

.aibcg-section h2 {
	margin-top: 0;
	color: #23282d;
	border-bottom: 1px solid #eee;
	padding-bottom: 10px;
}

.aibcg-troubleshooting .aibcg-issue {
	background: #f9f9f9;
	border-left: 4px solid #0073aa;
	padding: 15px;
	margin-bottom: 15px;
	border-radius: 0 4px 4px 0;
}

.aibcg-troubleshooting .aibcg-issue h4 {
	margin-top: 0;
	color: #0073aa;
}

.aibcg-troubleshooting .aibcg-issue ol,
.aibcg-troubleshooting .aibcg-issue ul {
	margin-left: 20px;
}

.aibcg-troubleshooting .aibcg-issue li {
	margin-bottom: 5px;
}

.notice-info {
	border-left-color: #0073aa;
}

.notice-info h3 {
	margin-top: 0;
	color: #0073aa;
}

.notice-info ul,
.notice-info ol {
	margin-left: 20px;
}

.notice-info li {
	margin-bottom: 5px;
}
</style>

<script>
jQuery(document).ready(function($) {
	// Simple provider switching
	$('#aibcg_ai_provider').on('change', function() {
		var selectedProvider = $(this).val();
		
		// Hide all provider settings
		$('.provider-settings').hide();
		
		// Show the selected provider's settings
		$('#' + selectedProvider + '-settings').show();
		
		// Update model options based on provider
		var modelSelect = $('#aibcg_default_model');
		modelSelect.empty();
		
		if (selectedProvider === 'openai') {
			modelSelect.append('<option value="gpt-3.5-turbo">GPT-3.5 Turbo (Fast & Cost-effective)</option>');
			modelSelect.append('<option value="gpt-4">GPT-4 (High Quality)</option>');
			modelSelect.append('<option value="gpt-4-turbo">GPT-4 Turbo (Latest & Recommended)</option>');
		} else if (selectedProvider === 'huggingface') {
			modelSelect.append('<option value="microsoft/DialoGPT-medium">DialoGPT Medium (Good Quality)</option>');
			modelSelect.append('<option value="gpt2">GPT-2 (Fast)</option>');
			modelSelect.append('<option value="EleutherAI/gpt-neo-125M">GPT-Neo 125M (Lightweight)</option>');
		} else if (selectedProvider === 'google') {
			modelSelect.append('<option value="gemini-pro">Gemini Pro (Excellent Quality)</option>');
			modelSelect.append('<option value="gemini-pro-vision">Gemini Pro Vision (With Image Support)</option>');
		} else if (selectedProvider === 'ollama') {
			modelSelect.append('<option value="llama2">Llama 2 (Good Quality)</option>');
			modelSelect.append('<option value="mistral">Mistral (Fast & Efficient)</option>');
			modelSelect.append('<option value="codellama">Code Llama (Code Focused)</option>');
		}
	});
	
	// Initialize on page load
	$('#aibcg_ai_provider').trigger('change');
	
	// Temperature slider
	$('#aibcg_temperature').on('input', function() {
		$('#temperature-value').text($(this).val());
	});
});
</script> 