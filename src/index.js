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
	upload,
	arrowRight
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
	const [error, setError] = useState('');
	const [selectedTemplate, setSelectedTemplate] = useState('');
	const [showAdvanced, setShowAdvanced] = useState(false);
	const [isInserting, setIsInserting] = useState(false);
	const [successMessage, setSuccessMessage] = useState('');
	const [autoInsert, setAutoInsert] = useState(false);
	
	const {
		prompt = '',
		contentType = 'blog_post',
		tone = 'professional',
		length = 500,
		content = '',
		useTemplate = false,
		templateId = '',
		lastGenerated = ''
	} = attributes;

	// Get available templates from WordPress
	const templates = window.aibcgData?.templates || [];
	const settings = window.aibcgData?.settings || {};

	// Block props for the main container
	const blockProps = useBlockProps({
		className: 'aibcg-content-generator'
	});

	// Get dispatch functions
	const { replaceInnerBlocks, insertBlocks } = useDispatch(blockEditorStore);
	const { getBlockIndex, getBlockOrder } = useSelect(select => ({
		getBlockIndex: select(blockEditorStore).getBlockIndex,
		getBlockOrder: select(blockEditorStore).getBlockOrder
	}), []);

	/**
	 * Generate content using AI
	 */
	const generateContent = async () => {
		if (!prompt.trim()) {
			setError(__('Please enter a prompt to generate content.', 'ai-blog-content-generator'));
			return;
		}

		// Check if any AI provider is configured
		const hasProvider = settings.provider && (
			settings.provider === 'huggingface' || 
			(settings.provider === 'openai' && settings.apiKey) ||
			(settings.provider === 'google' && settings.googleKey) ||
			settings.provider === 'ollama'
		);

		if (!hasProvider) {
			setError(__('AI provider not configured. Please configure it in the plugin settings.', 'ai-blog-content-generator'));
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
				// Replace existing content with new generated content
				setAttributes({ content: data.data.content });
				
				// Show success message
				if (autoInsert) {
					setTimeout(() => {
						// Auto-insert content after a short delay if user doesn't clear it
						if (data.data.content.trim()) {
							insertContentIntoPost();
						}
					}, 2000);
				}
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
	 * Insert generated content into the main post content
	 */
	const insertContentIntoPost = async () => {
		if (!content.trim()) {
			setError(__('No content to insert. Please generate content first.', 'ai-blog-content-generator'));
			return;
		}

		setIsInserting(true);
		setError('');
		setSuccessMessage('');

		try {
			// Create a paragraph block with the generated content
			const { createBlock } = await import('@wordpress/blocks');
			const paragraphBlock = createBlock('core/paragraph', {
				content: content
			});

			// Get current block index
			const currentBlockIndex = getBlockIndex(clientId);
			
			// Insert the paragraph block after the current AI generator block
			insertBlocks(paragraphBlock, currentBlockIndex + 1);

			// Clear the content from the generator block
			setAttributes({ content: '' });
			
			// Show success message
			setSuccessMessage(__('Content successfully inserted into your post!', 'ai-blog-content-generator'));
			
			// Clear success message after 3 seconds
			setTimeout(() => {
				setSuccessMessage('');
			}, 3000);

		} catch (err) {
			setError(__('Failed to insert content into post.', 'ai-blog-content-generator'));
		} finally {
			setIsInserting(false);
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
	 * Clear content
	 */
	const clearContent = () => {
		setAttributes({ content: '' });
		setError('');
		setSuccessMessage('');
		// Clear inner blocks
		replaceInnerBlocks(clientId, [], false);
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
						
						<div className="aibcg-advanced-toggles">
							<ToggleControl
								label={__('Auto-insert content into post after generation', 'ai-blog-content-generator')}
								help={__('Automatically insert generated content into your post after 2 seconds', 'ai-blog-content-generator')}
								checked={autoInsert}
								onChange={setAutoInsert}
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

				{/* Success Message */}
				{successMessage && (
					<Notice status="success" isDismissible={false}>
						{successMessage}
					</Notice>
				)}

				{/* Content Area */}
				<div className="aibcg-content-area">
					<div className="aibcg-content-header">
						<h4>{__('Generated Content', 'ai-blog-content-generator')}</h4>
						{content && (
							<div className="aibcg-content-actions">
								<Button
									variant="primary"
									icon={arrowRight}
									onClick={insertContentIntoPost}
									disabled={isInserting}
									size="small"
									className="aibcg-insert-btn"
								>
									{isInserting ? (
										<>
											<Spinner />
											{__('Inserting...', 'ai-blog-content-generator')}
										</>
									) : (
										__('Insert into Post', 'ai-blog-content-generator')
									)}
								</Button>
								<Button
									variant="secondary"
									icon={trash}
									onClick={clearContent}
									size="small"
								>
									{__('Clear', 'ai-blog-content-generator')}
								</Button>
							</div>
						)}
					</div>
					<div className="aibcg-editor-content">
						<RichText
							value={content}
							onChange={(value) => setAttributes({ content: value })}
							placeholder={__('Generated content will appear here and can be edited before inserting into your post...', 'ai-blog-content-generator')}
							tagName="div"
							className="aibcg-content-editor"
						/>
					</div>
				</div>
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
		align: ['wide', 'full'],
		reusable: false
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
		},
		lastGenerated: {
			type: 'string',
			default: ''
		}
	},
	edit: AIContentGeneratorBlock,
	save: ({ attributes }) => {
		const blockProps = useBlockProps.save({
			className: 'aibcg-content-generator'
		});

		// Only save the block structure, not the generated content
		// This prevents the content from being lost when editing
		return (
			<div {...blockProps}>
				{/* This block is for generating content only */}
				{/* Generated content should be inserted as separate blocks */}
			</div>
		);
	}
}); 