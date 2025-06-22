/**
 * WordPress dependencies
 */
import { registerBlockType } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';
import { 
	useBlockProps, 
	RichText, 
	InspectorControls,
	useInnerBlocksProps,
	store as blockEditorStore
} from '@wordpress/block-editor';
import { 
	PanelBody, 
	TextControl, 
	SelectControl, 
	RangeControl, 
	Button, 
	Spinner,
	Notice,
	Card,
	CardBody,
	CardHeader,
	ToggleControl,
	TextareaControl
} from '@wordpress/components';
import { useState, useEffect } from '@wordpress/element';
import { useSelect, useDispatch } from '@wordpress/data';
import { 
	cog, 
	plus, 
	edit, 
	trash,
	download,
	upload
} from '@wordpress/icons';

/**
 * Internal dependencies
 */
import './style.scss';

/**
 * AI Content Generator Block Component
 */
function AIContentGeneratorBlock({ attributes, setAttributes, clientId }) {
	const [isGenerating, setIsGenerating] = useState(false);
	const [generatedContent, setGeneratedContent] = useState('');
	const [error, setError] = useState('');
	const [selectedTemplate, setSelectedTemplate] = useState('');
	const [showAdvanced, setShowAdvanced] = useState(false);
	
	const {
		prompt = '',
		contentType = 'blog_post',
		tone = 'professional',
		length = 500,
		content = '',
		useTemplate = false,
		templateId = ''
	} = attributes;

	// Get available templates from WordPress
	const templates = window.aibcgData?.templates || [];
	const settings = window.aibcgData?.settings || {};

	// Block props for the main container
	const blockProps = useBlockProps({
		className: 'aibcg-content-generator'
	});

	// Inner blocks props for the content area
	const innerBlocksProps = useInnerBlocksProps(
		{ className: 'aibcg-generated-content' },
		{
			allowedBlocks: ['core/paragraph', 'core/heading', 'core/list', 'core/quote'],
			template: [['core/paragraph', { content: '' }]]
		}
	);

	/**
	 * Generate content using AI
	 */
	const generateContent = async () => {
		if (!prompt.trim()) {
			setError(__('Please enter a prompt to generate content.', 'ai-blog-content-generator'));
			return;
		}

		if (!settings.apiKey) {
			setError(__('OpenAI API key not configured. Please configure it in the plugin settings.', 'ai-blog-content-generator'));
			return;
		}

		setIsGenerating(true);
		setError('');

		try {
			const formData = new FormData();
			formData.append('action', 'aibcg_generate_content');
			formData.append('nonce', window.aibcgData.nonce);
			formData.append('prompt', prompt);
			formData.append('content_type', contentType);
			formData.append('tone', tone);
			formData.append('length', length);

			const response = await fetch(window.aibcgData.ajaxUrl, {
				method: 'POST',
				body: formData
			});

			const data = await response.json();

			if (data.success) {
				setGeneratedContent(data.data.content);
				setAttributes({ content: data.data.content });
			} else {
				setError(data.data || __('An error occurred while generating content.', 'ai-blog-content-generator'));
			}
		} catch (err) {
			setError(__('Network error. Please check your connection and try again.', 'ai-blog-content-generator'));
		} finally {
			setIsGenerating(false);
		}
	};

	/**
	 * Load template data
	 */
	const loadTemplate = (template) => {
		setAttributes({
			prompt: template.prompt,
			contentType: template.content_type,
			tone: template.tone,
			length: template.length
		});
		setSelectedTemplate(template.name);
	};

	/**
	 * Insert generated content into editor
	 */
	const insertContent = () => {
		if (generatedContent) {
			// Replace the inner blocks with the generated content
			const blocks = wp.blocks.parse(generatedContent);
			// This would need to be implemented with the block editor's replaceInnerBlocks
			setAttributes({ content: generatedContent });
		}
	};

	/**
	 * Clear generated content
	 */
	const clearContent = () => {
		setGeneratedContent('');
		setAttributes({ content: '' });
		setError('');
	};

	return (
		<div {...blockProps}>
			{/* Main Content Area */}
			<div className="aibcg-main-content">
				{/* Header */}
				<div className="aibcg-header">
					<div className="aibcg-header-left">
						<cog className="aibcg-icon" />
						<h3>{__('AI Content Generator', 'ai-blog-content-generator')}</h3>
					</div>
					<div className="aibcg-header-right">
						<Button
							variant="secondary"
							icon={showAdvanced ? 'arrow-up' : 'arrow-down'}
							onClick={() => setShowAdvanced(!showAdvanced)}
						>
							{showAdvanced ? __('Hide Advanced', 'ai-blog-content-generator') : __('Show Advanced', 'ai-blog-content-generator')}
						</Button>
					</div>
				</div>

				{/* Template Selection */}
				{templates.length > 0 && (
					<div className="aibcg-template-section">
						<SelectControl
							label={__('Load Template', 'ai-blog-content-generator')}
							value={selectedTemplate}
							options={[
								{ label: __('Select a template...', 'ai-blog-content-generator'), value: '' },
								...templates.map(template => ({
									label: template.name,
									value: template.name
								}))
							]}
							onChange={(value) => {
								setSelectedTemplate(value);
								if (value) {
									const template = templates.find(t => t.name === value);
									if (template) {
										loadTemplate(template);
									}
								}
							}}
						/>
					</div>
				)}

				{/* Prompt Input */}
				<div className="aibcg-prompt-section">
					<TextareaControl
						label={__('Content Prompt', 'ai-blog-content-generator')}
						value={prompt}
						onChange={(value) => setAttributes({ prompt: value })}
						placeholder={__('Describe what content you want to generate...', 'ai-blog-content-generator')}
						rows={3}
					/>
				</div>

				{/* Advanced Options */}
				{showAdvanced && (
					<div className="aibcg-advanced-options">
						<div className="aibcg-options-grid">
							<SelectControl
								label={__('Content Type', 'ai-blog-content-generator')}
								value={contentType}
								options={[
									{ label: __('Blog Post', 'ai-blog-content-generator'), value: 'blog_post' },
									{ label: __('Article', 'ai-blog-content-generator'), value: 'article' },
									{ label: __('Product Description', 'ai-blog-content-generator'), value: 'product_description' },
									{ label: __('Social Media Content', 'ai-blog-content-generator'), value: 'social_media' },
									{ label: __('Email', 'ai-blog-content-generator'), value: 'email' }
								]}
								onChange={(value) => setAttributes({ contentType: value })}
							/>

							<SelectControl
								label={__('Tone', 'ai-blog-content-generator')}
								value={tone}
								options={[
									{ label: __('Professional', 'ai-blog-content-generator'), value: 'professional' },
									{ label: __('Casual', 'ai-blog-content-generator'), value: 'casual' },
									{ label: __('Friendly', 'ai-blog-content-generator'), value: 'friendly' },
									{ label: __('Formal', 'ai-blog-content-generator'), value: 'formal' },
									{ label: __('Conversational', 'ai-blog-content-generator'), value: 'conversational' },
									{ label: __('Enthusiastic', 'ai-blog-content-generator'), value: 'enthusiastic' }
								]}
								onChange={(value) => setAttributes({ tone: value })}
							/>

							<RangeControl
								label={__('Target Length (words)', 'ai-blog-content-generator')}
								value={length}
								onChange={(value) => setAttributes({ length: value })}
								min={100}
								max={2000}
								step={50}
							/>
						</div>
					</div>
				)}

				{/* Generate Button */}
				<div className="aibcg-actions">
					<Button
						variant="primary"
						onClick={generateContent}
						disabled={isGenerating || !prompt.trim()}
						className="aibcg-generate-btn"
					>
						{isGenerating ? (
							<>
								<Spinner />
								{__('Generating...', 'ai-blog-content-generator')}
							</>
						) : (
							<>
								<cog />
								{__('Generate Content', 'ai-blog-content-generator')}
							</>
						)}
					</Button>
				</div>

				{/* Error Display */}
				{error && (
					<Notice status="error" isDismissible={false}>
						{error}
					</Notice>
				)}

				{/* Generated Content Display */}
				{generatedContent && (
					<div className="aibcg-generated-section">
						<div className="aibcg-generated-header">
							<h4>{__('Generated Content', 'ai-blog-content-generator')}</h4>
							<div className="aibcg-generated-actions">
								<Button
									variant="secondary"
									icon={download}
									onClick={insertContent}
								>
									{__('Insert into Editor', 'ai-blog-content-generator')}
								</Button>
								<Button
									variant="secondary"
									icon={trash}
									onClick={clearContent}
								>
									{__('Clear', 'ai-blog-content-generator')}
								</Button>
							</div>
						</div>
						<div className="aibcg-generated-content">
							<RichText
								value={generatedContent}
								onChange={(value) => setGeneratedContent(value)}
								placeholder={__('Generated content will appear here...', 'ai-blog-content-generator')}
								tagName="div"
								className="aibcg-content-preview"
							/>
						</div>
					</div>
				)}

				{/* Content Area for Inserted Content */}
				{content && (
					<div className="aibcg-content-area">
						<div className="aibcg-content-header">
							<h4>{__('Editor Content', 'ai-blog-content-generator')}</h4>
						</div>
						<div {...innerBlocksProps} />
					</div>
				)}
			</div>
		</div>
	);
}

/**
 * Register the block
 */
registerBlockType('ai-blog-content-generator/content-generator', {
	title: __('AI Content Generator', 'ai-blog-content-generator'),
	description: __('Generate AI-powered content directly in the editor.', 'ai-blog-content-generator'),
	category: 'widgets',
	icon: cog,
	keywords: [
		__('AI', 'ai-blog-content-generator'),
		__('Content', 'ai-blog-content-generator'),
		__('Generator', 'ai-blog-content-generator'),
		__('Blog', 'ai-blog-content-generator')
	],
	supports: {
		html: false,
		align: ['wide', 'full']
	},
	attributes: {
		prompt: {
			type: 'string',
			default: ''
		},
		contentType: {
			type: 'string',
			default: 'blog_post'
		},
		tone: {
			type: 'string',
			default: 'professional'
		},
		length: {
			type: 'number',
			default: 500
		},
		content: {
			type: 'string',
			default: ''
		},
		useTemplate: {
			type: 'boolean',
			default: false
		},
		templateId: {
			type: 'string',
			default: ''
		}
	},
	edit: AIContentGeneratorBlock,
	save: ({ attributes }) => {
		const blockProps = useBlockProps.save({
			className: 'aibcg-content-generator'
		});

		return (
			<div {...blockProps}>
				<div className="aibcg-generated-content">
					{attributes.content && (
						<div dangerouslySetInnerHTML={{ __html: attributes.content }} />
					)}
				</div>
			</div>
		);
	}
}); 