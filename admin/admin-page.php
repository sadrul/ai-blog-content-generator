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
	
	<div class="aibcg-admin-container">
		<div class="aibcg-admin-content">
			<!-- API Settings Section -->
			<div class="aibcg-section">
				<h2><?php _e( 'API Configuration', 'ai-blog-content-generator' ); ?></h2>
				<p><?php _e( 'Configure your OpenAI API settings to enable AI content generation.', 'ai-blog-content-generator' ); ?></p>
				
				<form method="post" action="options.php">
					<?php settings_fields( 'aibcg_settings' ); ?>
					
					<table class="form-table">
						<tr>
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
								</p>
							</td>
						</tr>
						
						<tr>
							<th scope="row">
								<label for="aibcg_default_model"><?php _e( 'Default Model', 'ai-blog-content-generator' ); ?></label>
							</th>
							<td>
								<select id="aibcg_default_model" name="aibcg_default_model">
									<option value="gpt-3.5-turbo" <?php selected( get_option( 'aibcg_default_model' ), 'gpt-3.5-turbo' ); ?>>
										GPT-3.5 Turbo (Fast & Cost-effective)
									</option>
									<option value="gpt-4" <?php selected( get_option( 'aibcg_default_model' ), 'gpt-4' ); ?>>
										GPT-4 (High Quality)
									</option>
									<option value="gpt-4-turbo" <?php selected( get_option( 'aibcg_default_model' ), 'gpt-4-turbo' ); ?>>
										GPT-4 Turbo (Latest & Recommended)
									</option>
								</select>
								<p class="description">
									<?php _e( 'Choose the AI model for content generation. GPT-4 models provide better quality but cost more.', 'ai-blog-content-generator' ); ?>
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
			
			<!-- Content Templates Section -->
			<div class="aibcg-section">
				<h2><?php _e( 'Content Templates', 'ai-blog-content-generator' ); ?></h2>
				<p><?php _e( 'Create and manage reusable content templates for quick generation.', 'ai-blog-content-generator' ); ?></p>
				
				<div class="aibcg-templates-container">
					<div class="aibcg-template-form">
						<h3><?php _e( 'Add New Template', 'ai-blog-content-generator' ); ?></h3>
						<form id="aibcg-template-form">
							<table class="form-table">
								<tr>
									<th scope="row">
										<label for="template-name"><?php _e( 'Template Name', 'ai-blog-content-generator' ); ?></label>
									</th>
									<td>
										<input type="text" id="template-name" name="name" class="regular-text" required />
									</td>
								</tr>
								
								<tr>
									<th scope="row">
										<label for="template-prompt"><?php _e( 'Default Prompt', 'ai-blog-content-generator' ); ?></label>
									</th>
									<td>
										<textarea id="template-prompt" name="prompt" rows="3" class="large-text" required></textarea>
										<p class="description">
											<?php _e( 'The default prompt for this template. Users can modify this when generating content.', 'ai-blog-content-generator' ); ?>
										</p>
									</td>
								</tr>
								
								<tr>
									<th scope="row">
										<label for="template-content-type"><?php _e( 'Content Type', 'ai-blog-content-generator' ); ?></label>
									</th>
									<td>
										<select id="template-content-type" name="content_type">
											<option value="blog_post"><?php _e( 'Blog Post', 'ai-blog-content-generator' ); ?></option>
											<option value="article"><?php _e( 'Article', 'ai-blog-content-generator' ); ?></option>
											<option value="product_description"><?php _e( 'Product Description', 'ai-blog-content-generator' ); ?></option>
											<option value="social_media"><?php _e( 'Social Media Content', 'ai-blog-content-generator' ); ?></option>
											<option value="email"><?php _e( 'Email', 'ai-blog-content-generator' ); ?></option>
										</select>
									</td>
								</tr>
								
								<tr>
									<th scope="row">
										<label for="template-tone"><?php _e( 'Default Tone', 'ai-blog-content-generator' ); ?></label>
									</th>
									<td>
										<select id="template-tone" name="tone">
											<option value="professional"><?php _e( 'Professional', 'ai-blog-content-generator' ); ?></option>
											<option value="casual"><?php _e( 'Casual', 'ai-blog-content-generator' ); ?></option>
											<option value="friendly"><?php _e( 'Friendly', 'ai-blog-content-generator' ); ?></option>
											<option value="formal"><?php _e( 'Formal', 'ai-blog-content-generator' ); ?></option>
											<option value="conversational"><?php _e( 'Conversational', 'ai-blog-content-generator' ); ?></option>
											<option value="enthusiastic"><?php _e( 'Enthusiastic', 'ai-blog-content-generator' ); ?></option>
										</select>
									</td>
								</tr>
								
								<tr>
									<th scope="row">
										<label for="template-length"><?php _e( 'Default Length (words)', 'ai-blog-content-generator' ); ?></label>
									</th>
									<td>
										<input type="number" id="template-length" name="length" value="500" min="100" max="2000" class="small-text" />
									</td>
								</tr>
							</table>
							
							<button type="submit" class="button button-primary">
								<?php _e( 'Save Template', 'ai-blog-content-generator' ); ?>
							</button>
						</form>
					</div>
					
					<div class="aibcg-templates-list">
						<h3><?php _e( 'Saved Templates', 'ai-blog-content-generator' ); ?></h3>
						<div id="aibcg-templates-list">
							<?php
							$templates = get_option( 'aibcg_content_templates', array() );
							if ( empty( $templates ) ) {
								echo '<p>' . __( 'No templates saved yet.', 'ai-blog-content-generator' ) . '</p>';
							} else {
								echo '<ul class="aibcg-templates">';
								foreach ( $templates as $index => $template ) {
									echo '<li class="aibcg-template-item">';
									echo '<strong>' . esc_html( $template['name'] ) . '</strong>';
									echo '<span class="template-type">' . esc_html( ucfirst( $template['content_type'] ) ) . '</span>';
									echo '<span class="template-tone">' . esc_html( ucfirst( $template['tone'] ) ) . '</span>';
									echo '<button class="button button-small delete-template" data-index="' . $index . '">' . __( 'Delete', 'ai-blog-content-generator' ) . '</button>';
									echo '</li>';
								}
								echo '</ul>';
							}
							?>
						</div>
					</div>
				</div>
			</div>
			
			<!-- Usage Instructions -->
			<div class="aibcg-section">
				<h2><?php _e( 'How to Use', 'ai-blog-content-generator' ); ?></h2>
				<div class="aibcg-instructions">
					<ol>
						<li><?php _e( 'Configure your OpenAI API key above.', 'ai-blog-content-generator' ); ?></li>
						<li><?php _e( 'Create content templates for quick access.', 'ai-blog-content-generator' ); ?></li>
						<li><?php _e( 'In the Gutenberg editor, add the "AI Content Generator" block.', 'ai-blog-content-generator' ); ?></li>
						<li><?php _e( 'Enter your prompt or select a template.', 'ai-blog-content-generator' ); ?></li>
						<li><?php _e( 'Choose content type, tone, and length.', 'ai-blog-content-generator' ); ?></li>
						<li><?php _e( 'Click "Generate Content" and wait for the AI response.', 'ai-blog-content-generator' ); ?></li>
						<li><?php _e( 'Review and edit the generated content as needed.', 'ai-blog-content-generator' ); ?></li>
					</ol>
				</div>
			</div>
		</div>
		
		<!-- Sidebar -->
		<div class="aibcg-sidebar">
			<div class="aibcg-widget">
				<h3><?php _e( 'Quick Stats', 'ai-blog-content-generator' ); ?></h3>
				<div class="aibcg-stats">
					<div class="stat-item">
						<span class="stat-number"><?php echo count( get_option( 'aibcg_content_templates', array() ) ); ?></span>
						<span class="stat-label"><?php _e( 'Templates', 'ai-blog-content-generator' ); ?></span>
					</div>
					<div class="stat-item">
						<span class="stat-number"><?php echo get_option( 'aibcg_default_model', 'gpt-3.5-turbo' ); ?></span>
						<span class="stat-label"><?php _e( 'Active Model', 'ai-blog-content-generator' ); ?></span>
					</div>
				</div>
			</div>
			
			<div class="aibcg-widget">
				<h3><?php _e( 'Support', 'ai-blog-content-generator' ); ?></h3>
				<p><?php _e( 'Need help? Check out our documentation or contact support.', 'ai-blog-content-generator' ); ?></p>
				<p>
					<a href="#" class="button button-secondary"><?php _e( 'Documentation', 'ai-blog-content-generator' ); ?></a>
				</p>
			</div>
		</div>
	</div>
</div>

<style>
.aibcg-admin-container {
	display: flex;
	gap: 30px;
	margin-top: 20px;
}

.aibcg-admin-content {
	flex: 1;
}

.aibcg-sidebar {
	width: 300px;
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

.aibcg-templates-container {
	display: grid;
	grid-template-columns: 1fr 1fr;
	gap: 20px;
}

.aibcg-template-form,
.aibcg-templates-list {
	background: #f9f9f9;
	padding: 15px;
	border-radius: 4px;
}

.aibcg-templates {
	list-style: none;
	margin: 0;
	padding: 0;
}

.aibcg-template-item {
	background: #fff;
	padding: 10px;
	margin-bottom: 10px;
	border-radius: 4px;
	border: 1px solid #ddd;
	display: flex;
	justify-content: space-between;
	align-items: center;
}

.template-type,
.template-tone {
	background: #0073aa;
	color: #fff;
	padding: 2px 8px;
	border-radius: 3px;
	font-size: 11px;
	margin: 0 5px;
}

.aibcg-instructions ol {
	margin-left: 20px;
}

.aibcg-instructions li {
	margin-bottom: 10px;
	line-height: 1.6;
}

.aibcg-widget {
	background: #fff;
	border: 1px solid #ccd0d4;
	border-radius: 4px;
	padding: 15px;
	margin-bottom: 20px;
}

.aibcg-widget h3 {
	margin-top: 0;
	color: #23282d;
}

.aibcg-stats {
	display: flex;
	gap: 20px;
}

.stat-item {
	text-align: center;
}

.stat-number {
	display: block;
	font-size: 24px;
	font-weight: bold;
	color: #0073aa;
}

.stat-label {
	font-size: 12px;
	color: #666;
}

#temperature-value {
	margin-left: 10px;
	font-weight: bold;
	color: #0073aa;
}

@media (max-width: 1200px) {
	.aibcg-admin-container {
		flex-direction: column;
	}
	
	.aibcg-sidebar {
		width: 100%;
	}
	
	.aibcg-templates-container {
		grid-template-columns: 1fr;
	}
}
</style>

<script>
jQuery(document).ready(function($) {
	// Temperature slider
	$('#aibcg_temperature').on('input', function() {
		$('#temperature-value').text($(this).val());
	});
	
	// Template form submission
	$('#aibcg-template-form').on('submit', function(e) {
		e.preventDefault();
		
		var formData = new FormData(this);
		formData.append('action', 'aibcg_save_template');
		formData.append('nonce', '<?php echo wp_create_nonce( 'aibcg_nonce' ); ?>');
		
		$.ajax({
			url: ajaxurl,
			type: 'POST',
			data: formData,
			processData: false,
			contentType: false,
			success: function(response) {
				if (response.success) {
					alert(response.data);
					location.reload();
				} else {
					alert('Error: ' + response.data);
				}
			},
			error: function() {
				alert('An error occurred. Please try again.');
			}
		});
	});
	
	// Delete template
	$('.delete-template').on('click', function() {
		if (confirm('Are you sure you want to delete this template?')) {
			var index = $(this).data('index');
			// Add delete functionality here
		}
	});
});
</script> 