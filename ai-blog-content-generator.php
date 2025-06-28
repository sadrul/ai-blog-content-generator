<?php
/**
 * Plugin Name: AI Blog Content Generator
 * Plugin URI: https://github.com/sadrul/ai-blog-content-generator
 * Description: Generate AI-powered blog content directly in the Gutenberg editor with customizable templates and settings.
 * Version: 1.0.0
 * Author: K M Sadrul Ula
 * Author URI: https://github.com/sadrul
 * Author Email: kmsadrulula@gmail.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: ai-blog-content-generator
 * Domain Path: /languages
 * Requires at least: 5.0
 * Tested up to: 6.4
 * Requires PHP: 7.4
 *
 * @package AI_Blog_Content_Generator
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define plugin constants.
define( 'AIBCG_VERSION', '1.0.0' );
define( 'AIBCG_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'AIBCG_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'AIBCG_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

/**
 * Main plugin class.
 */
class AI_Blog_Content_Generator {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->init_hooks();
	}

	/**
	 * Initialize hooks.
	 */
	private function init_hooks() {
		add_action( 'init', array( $this, 'init' ) );
		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_block_assets' ) );
		add_action( 'wp_ajax_aibcg_generate_content', array( $this, 'ajax_generate_content' ) );
		add_action( 'wp_ajax_aibcg_save_template', array( $this, 'ajax_save_template' ) );
		add_action( 'wp_ajax_aibcg_get_templates', array( $this, 'ajax_get_templates' ) );
	}

	/**
	 * Initialize the plugin.
	 */
	public function init() {
		// Load text domain for internationalization.
		load_plugin_textdomain( 'ai-blog-content-generator', false, dirname( AIBCG_PLUGIN_BASENAME ) . '/languages' );

		// Register block.
		$this->register_block();
	}

	/**
	 * Register the Gutenberg block.
	 */
	private function register_block() {
		register_block_type( AIBCG_PLUGIN_DIR . 'build', array(
			'render_callback' => array( $this, 'render_block' ),
		) );
	}

	/**
	 * Render the block on the frontend.
	 *
	 * @param array $attributes Block attributes.
	 * @return string Rendered block HTML.
	 */
	public function render_block( $attributes ) {
		$content = isset( $attributes['content'] ) ? $attributes['content'] : '';
		$className = isset( $attributes['className'] ) ? $attributes['className'] : '';
		
		return sprintf(
			'<div class="aibcg-content %s">%s</div>',
			esc_attr( $className ),
			wp_kses_post( $content )
		);
	}

	/**
	 * Add admin menu.
	 */
	public function add_admin_menu() {
		add_options_page(
			__( 'AI Blog Content Generator', 'ai-blog-content-generator' ),
			__( 'AI Content Generator', 'ai-blog-content-generator' ),
			'manage_options',
			'ai-blog-content-generator',
			array( $this, 'admin_page' )
		);
	}

	/**
	 * Register plugin settings.
	 */
	public function register_settings() {
		register_setting( 'aibcg_settings', 'aibcg_ai_provider', array( 'default' => 'huggingface' ) );
		register_setting( 'aibcg_settings', 'aibcg_openai_api_key' );
		register_setting( 'aibcg_settings', 'aibcg_huggingface_api_key' );
		register_setting( 'aibcg_settings', 'aibcg_google_api_key' );
		register_setting( 'aibcg_settings', 'aibcg_ollama_url', array( 'default' => 'http://localhost:11434' ) );
		register_setting( 'aibcg_settings', 'aibcg_default_model', array( 'default' => 'microsoft/DialoGPT-medium' ) );
		register_setting( 'aibcg_settings', 'aibcg_max_tokens', array( 'default' => 1000 ) );
		register_setting( 'aibcg_settings', 'aibcg_temperature', array( 'default' => 0.7 ) );
		register_setting( 'aibcg_settings', 'aibcg_content_templates', array( 'default' => array() ) );
	}

	/**
	 * Admin settings page.
	 */
	public function admin_page() {
		include AIBCG_PLUGIN_DIR . 'admin/admin-page.php';
	}

	/**
	 * Enqueue block assets.
	 */
	public function enqueue_block_assets() {
		wp_enqueue_script(
			'aibcg-block-editor',
			AIBCG_PLUGIN_URL . 'build/index.js',
			array( 'wp-blocks', 'wp-element', 'wp-editor', 'wp-components', 'wp-i18n', 'wp-data' ),
			AIBCG_VERSION,
			true
		);

		wp_enqueue_style(
			'aibcg-block-editor',
			AIBCG_PLUGIN_URL . 'build/index.css',
			array(),
			AIBCG_VERSION
		);

		// Localize script with settings and nonce.
		wp_localize_script( 'aibcg-block-editor', 'aibcgData', array(
			'ajaxUrl' => admin_url( 'admin-ajax.php' ),
			'nonce' => wp_create_nonce( 'aibcg_nonce' ),
			'settings' => array(
				'provider' => get_option( 'aibcg_ai_provider', 'huggingface' ),
				'apiKey' => get_option( 'aibcg_openai_api_key' ),
				'huggingfaceKey' => get_option( 'aibcg_huggingface_api_key' ),
				'googleKey' => get_option( 'aibcg_google_api_key' ),
				'model' => get_option( 'aibcg_default_model', 'microsoft/DialoGPT-medium' ),
				'maxTokens' => get_option( 'aibcg_max_tokens', 1000 ),
				'temperature' => get_option( 'aibcg_temperature', 0.7 ),
			),
			'templates' => get_option( 'aibcg_content_templates', array() ),
		) );
	}

	/**
	 * AJAX handler for content generation.
	 */
	public function ajax_generate_content() {
		// Verify nonce.
		if ( ! wp_verify_nonce( $_POST['nonce'], 'aibcg_nonce' ) ) {
			wp_die( __( 'Security check failed.', 'ai-blog-content-generator' ) );
		}

		// Check permissions.
		if ( ! current_user_can( 'edit_posts' ) ) {
			wp_die( __( 'Insufficient permissions.', 'ai-blog-content-generator' ) );
		}

		$prompt = sanitize_textarea_field( $_POST['prompt'] );
		$content_type = sanitize_text_field( $_POST['content_type'] );
		$tone = sanitize_text_field( $_POST['tone'] );
		$length = intval( $_POST['length'] );

		// Generate content using AI service.
		$content = $this->generate_ai_content( $prompt, $content_type, $tone, $length );

		if ( is_wp_error( $content ) ) {
			wp_send_json_error( $content->get_error_message() );
		}

		wp_send_json_success( array(
			'content' => $content,
			'prompt' => $prompt,
		) );
	}

	/**
	 * Generate content using AI service.
	 *
	 * @param string $prompt The user prompt.
	 * @param string $content_type Type of content to generate.
	 * @param string $tone Tone of the content.
	 * @param int $length Approximate length in words.
	 * @return string|WP_Error Generated content or error.
	 */
	private function generate_ai_content( $prompt, $content_type, $tone, $length ) {
		$provider = get_option( 'aibcg_ai_provider', 'openai' );
		
		// Build the full prompt based on content type and parameters.
		$full_prompt = $this->build_prompt( $prompt, $content_type, $tone, $length );
		
		switch ( $provider ) {
			case 'openai':
				return $this->generate_with_openai( $full_prompt );
			case 'huggingface':
				return $this->generate_with_huggingface( $full_prompt );
			case 'google':
				return $this->generate_with_google( $full_prompt );
			case 'ollama':
				return $this->generate_with_ollama( $full_prompt );
			default:
				return new WP_Error( 'invalid_provider', __( 'Invalid AI provider selected.', 'ai-blog-content-generator' ) );
		}
	}
	
	/**
	 * Generate content using OpenAI.
	 */
	private function generate_with_openai( $prompt ) {
		$api_key = get_option( 'aibcg_openai_api_key' );
		
		if ( empty( $api_key ) ) {
			return new WP_Error( 'no_api_key', __( 'OpenAI API key not configured.', 'ai-blog-content-generator' ) );
		}

		$response = wp_remote_post( 'https://api.openai.com/v1/chat/completions', array(
			'headers' => array(
				'Authorization' => 'Bearer ' . $api_key,
				'Content-Type' => 'application/json',
			),
			'body' => json_encode( array(
				'model' => get_option( 'aibcg_default_model', 'gpt-4-turbo' ),
				'messages' => array(
					array(
						'role' => 'system',
						'content' => 'You are a professional content writer who creates engaging, SEO-friendly blog content.',
					),
					array(
						'role' => 'user',
						'content' => $prompt,
					),
				),
				'max_tokens' => get_option( 'aibcg_max_tokens', 1000 ),
				'temperature' => get_option( 'aibcg_temperature', 0.7 ),
			) ),
			'timeout' => 60,
		) );

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$body = wp_remote_retrieve_body( $response );
		$data = json_decode( $body, true );

		if ( isset( $data['error'] ) ) {
			$error_message = $data['error']['message'];
			
			// Provide more helpful error messages for common issues
			if ( strpos( $error_message, 'quota' ) !== false || strpos( $error_message, 'billing' ) !== false ) {
				$error_message = __( 'API quota exceeded. Please check your OpenAI API billing and usage limits. ChatGPT Plus subscription does not include API access - you need a separate OpenAI API account.', 'ai-blog-content-generator' );
			} elseif ( strpos( $error_message, 'invalid_api_key' ) !== false ) {
				$error_message = __( 'Invalid API key. Please check your OpenAI API key in the plugin settings.', 'ai-blog-content-generator' );
			} elseif ( strpos( $error_message, 'rate_limit' ) !== false ) {
				$error_message = __( 'Rate limit exceeded. Please wait a moment and try again.', 'ai-blog-content-generator' );
			}
			
			return new WP_Error( 'api_error', $error_message );
		}

		if ( ! isset( $data['choices'][0]['message']['content'] ) ) {
			return new WP_Error( 'invalid_response', __( 'Invalid response from AI service.', 'ai-blog-content-generator' ) );
		}

		return $data['choices'][0]['message']['content'];
	}
	
	/**
	 * Generate content using Hugging Face (FREE).
	 */
	private function generate_with_huggingface( $prompt ) {
		$api_key = get_option( 'aibcg_huggingface_api_key' );
		$model = get_option( 'aibcg_default_model', 'microsoft/DialoGPT-medium' );
		
		// Hugging Face allows free usage without API key for some models
		$headers = array( 'Content-Type' => 'application/json' );
		if ( ! empty( $api_key ) ) {
			$headers['Authorization'] = 'Bearer ' . $api_key;
		}

		$response = wp_remote_post( 'https://api-inference.huggingface.co/models/' . $model, array(
			'headers' => $headers,
			'body' => json_encode( array(
				'inputs' => $prompt,
				'parameters' => array(
					'max_length' => get_option( 'aibcg_max_tokens', 1000 ),
					'temperature' => get_option( 'aibcg_temperature', 0.7 ),
					'do_sample' => true,
				),
			) ),
			'timeout' => 60,
		) );

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$body = wp_remote_retrieve_body( $response );
		$data = json_decode( $body, true );

		if ( isset( $data['error'] ) ) {
			return new WP_Error( 'api_error', $data['error'] );
		}

		if ( ! isset( $data[0]['generated_text'] ) ) {
			return new WP_Error( 'invalid_response', __( 'Invalid response from Hugging Face.', 'ai-blog-content-generator' ) );
		}

		return $data[0]['generated_text'];
	}
	
	/**
	 * Generate content using Google Gemini (FREE tier).
	 */
	private function generate_with_google( $prompt ) {
		$api_key = get_option( 'aibcg_google_api_key' );
		
		if ( empty( $api_key ) ) {
			return new WP_Error( 'no_api_key', __( 'Google API key not configured.', 'ai-blog-content-generator' ) );
		}

		$response = wp_remote_post( 'https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent?key=' . $api_key, array(
			'headers' => array( 'Content-Type' => 'application/json' ),
			'body' => json_encode( array(
				'contents' => array(
					array(
						'parts' => array(
							array( 'text' => $prompt ),
						),
					),
				),
				'generationConfig' => array(
					'maxOutputTokens' => get_option( 'aibcg_max_tokens', 1000 ),
					'temperature' => get_option( 'aibcg_temperature', 0.7 ),
				),
			) ),
			'timeout' => 60,
		) );

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$body = wp_remote_retrieve_body( $response );
		$data = json_decode( $body, true );

		if ( isset( $data['error'] ) ) {
			return new WP_Error( 'api_error', $data['error']['message'] );
		}

		if ( ! isset( $data['candidates'][0]['content']['parts'][0]['text'] ) ) {
			return new WP_Error( 'invalid_response', __( 'Invalid response from Google Gemini.', 'ai-blog-content-generator' ) );
		}

		return $data['candidates'][0]['content']['parts'][0]['text'];
	}
	
	/**
	 * Generate content using Ollama (FREE - local).
	 */
	private function generate_with_ollama( $prompt ) {
		// Ollama runs locally, so we need to check if it's available
		$ollama_url = get_option( 'aibcg_ollama_url', 'http://localhost:11434' );
		$model = get_option( 'aibcg_default_model', 'llama2' );
		$temperature = floatval( get_option( 'aibcg_temperature', 0.7 ) );
		$max_tokens = intval( get_option( 'aibcg_max_tokens', 1000 ) );
		
		// Debug info
		error_log( "Ollama request - URL: $ollama_url, Model: $model, Temperature: $temperature, Max tokens: $max_tokens" );
		
		$request_body = array(
			'model' => $model,
			'prompt' => $prompt,
			'stream' => false,
			'options' => array(
				'temperature' => $temperature,
				'num_predict' => $max_tokens,
			),
		);
		
		$response = wp_remote_post( $ollama_url . '/api/generate', array(
			'headers' => array( 'Content-Type' => 'application/json' ),
			'body' => json_encode( $request_body ),
			'timeout' => 120, // Longer timeout for local processing
		) );

		if ( is_wp_error( $response ) ) {
			error_log( "Ollama connection error: " . $response->get_error_message() );
			return new WP_Error( 'ollama_error', __( 'Ollama is not running. Please install and start Ollama on your server.', 'ai-blog-content-generator' ) );
		}

		$body = wp_remote_retrieve_body( $response );
		$data = json_decode( $body, true );

		if ( isset( $data['error'] ) ) {
			error_log( "Ollama API error: " . $data['error'] );
			return new WP_Error( 'api_error', $data['error'] );
		}

		if ( ! isset( $data['response'] ) ) {
			error_log( "Ollama invalid response: " . print_r( $data, true ) );
			return new WP_Error( 'invalid_response', __( 'Invalid response from Ollama.', 'ai-blog-content-generator' ) );
		}

		return $data['response'];
	}

	/**
	 * Build the full prompt for AI generation.
	 *
	 * @param string $prompt User prompt.
	 * @param string $content_type Content type.
	 * @param string $tone Tone.
	 * @param int $length Length.
	 * @return string Full prompt.
	 */
	private function build_prompt( $prompt, $content_type, $tone, $length ) {
		$templates = array(
			'blog_post' => 'Write a comprehensive blog post about: {prompt}. Make it engaging, informative, and well-structured with headings and subheadings.',
			'article' => 'Create a detailed article on: {prompt}. Include relevant facts, examples, and actionable insights.',
			'product_description' => 'Write a compelling product description for: {prompt}. Highlight benefits, features, and use cases.',
			'social_media' => 'Create engaging social media content about: {prompt}. Make it shareable and attention-grabbing.',
			'email' => 'Write a professional email about: {prompt}. Keep it concise and action-oriented.',
		);

		$template = isset( $templates[ $content_type ] ) ? $templates[ $content_type ] : $templates['blog_post'];
		$template = str_replace( '{prompt}', $prompt, $template );

		$full_prompt = $template . "\n\n";
		$full_prompt .= "Tone: " . ucfirst( $tone ) . "\n";
		$full_prompt .= "Target length: Approximately " . $length . " words\n";
		$full_prompt .= "Please ensure the content is original, engaging, and provides value to readers.";

		return $full_prompt;
	}

	/**
	 * AJAX handler for saving templates.
	 */
	public function ajax_save_template() {
		if ( ! wp_verify_nonce( $_POST['nonce'], 'aibcg_nonce' ) ) {
			wp_die( __( 'Security check failed.', 'ai-blog-content-generator' ) );
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( __( 'Insufficient permissions.', 'ai-blog-content-generator' ) );
		}

		$templates = get_option( 'aibcg_content_templates', array() );
		$template = array(
			'name' => sanitize_text_field( $_POST['name'] ),
			'prompt' => sanitize_textarea_field( $_POST['prompt'] ),
			'content_type' => sanitize_text_field( $_POST['content_type'] ),
			'tone' => sanitize_text_field( $_POST['tone'] ),
			'length' => intval( $_POST['length'] ),
		);

		$templates[] = $template;
		update_option( 'aibcg_content_templates', $templates );

		wp_send_json_success( __( 'Template saved successfully.', 'ai-blog-content-generator' ) );
	}

	/**
	 * AJAX handler for getting templates.
	 */
	public function ajax_get_templates() {
		if ( ! wp_verify_nonce( $_POST['nonce'], 'aibcg_nonce' ) ) {
			wp_die( __( 'Security check failed.', 'ai-blog-content-generator' ) );
		}

		$templates = get_option( 'aibcg_content_templates', array() );
		wp_send_json_success( $templates );
	}
}

// Initialize the plugin.
new AI_Blog_Content_Generator();

// Activation hook.
register_activation_hook( __FILE__, 'aibcg_activate' );

/**
 * Plugin activation function.
 */
function aibcg_activate() {
	// Set default options.
	add_option( 'aibcg_ai_provider', 'huggingface' ); // Default to free option
	add_option( 'aibcg_default_model', 'microsoft/DialoGPT-medium' ); // Default model for Hugging Face
	add_option( 'aibcg_max_tokens', 1000 );
	add_option( 'aibcg_temperature', 0.7 );
	add_option( 'aibcg_content_templates', array() );
	add_option( 'aibcg_ollama_url', 'http://localhost:11434' );
}

// Deactivation hook.
register_deactivation_hook( __FILE__, 'aibcg_deactivate' );

/**
 * Plugin deactivation function.
 */
function aibcg_deactivate() {
	// Clean up if needed.
} 