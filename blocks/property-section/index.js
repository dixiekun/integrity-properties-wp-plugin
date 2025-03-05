import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps, InnerBlocks, RichText } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';
import './editor.scss';

registerBlockType('integrity/property-section', {
    edit: ({ attributes, setAttributes }) => {
        const blockProps = useBlockProps({
            className: 'integrity-property-section',
        });
        return (
            <div {...blockProps}>
                <div className="integrity-property-section__header">
                    <RichText
                        tagName="h2"
                        className="integrity-property-section__title"
                        value={attributes.title}
                        onChange={(title) => setAttributes({ title })}
                        placeholder={__('Section Title')}
                    />
                    <RichText
                        tagName="p"
                        className="integrity-property-section__description"
                        value={attributes.description}
                        onChange={(description) => setAttributes({ description })}
                        placeholder={__('Section Description')}
                    />
                </div>
                <div className="integrity-property-section__content">
                    <InnerBlocks
                        allowedBlocks={['integrity/property-card']}
                        template={[
                            ['integrity/property-card'],
                            ['integrity/property-card']
                        ]}
                    />
                </div>
            </div>
        );
    },
    save: () => {
        return <InnerBlocks.Content />;
    },
});