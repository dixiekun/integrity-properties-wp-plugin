import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps, RichText } from '@wordpress/block-editor';

// Import the styles for the editor
import './editor.scss';

registerBlockType('integrity/property-section', {
    edit: ({ attributes, setAttributes }) => {
        const blockProps = useBlockProps({
            className: 'integrity-property-section',
        });
        
        // Access property data from global object
        const virginia = window.integrityProperties?.virginia;
        const maryland = window.integrityProperties?.maryland;
        
        return (
            <div {...blockProps}>
                <div className="integrity-property-section__header">
                    <RichText
                        tagName="h2"
                        className="integrity-property-section__title"
                        value={attributes.title}
                        onChange={(title) => setAttributes({ title })}
                        placeholder="Section Title"
                    />
                    <RichText
                        tagName="p"
                        className="integrity-property-section__description"
                        value={attributes.description}
                        onChange={(description) => setAttributes({ description })}
                        placeholder="Section Description"
                    />
                </div>
                
                <div className="integrity-property-section__content">
                    {/* Virginia Property Card (Hard-coded) */}
                    {virginia && (
                        <div className="property-card">
                            <div className="property-card__image-container">
                                <div className="property-card__featured-img-mask">
                                    <img 
                                        src={virginia.featured_image} 
                                        className="property-card__featured-img" 
                                        alt={virginia.name} 
                                    />
                                </div>
                                <div className="property-card__label">{virginia.community_label}</div>
                                <img 
                                    className="property-card__badge"
                                    src={virginia.badge}
                                    alt="Award Badge"
                                />
                            </div>
                            
                            <div className="property-card__price">
                                Priced from: {virginia.price}
                            </div>
                            
                            <h3 className="property-card__title">{virginia.name}</h3>
                            
                            <div className="property-card__excerpt">
                                {virginia.excerpt}
                            </div>
                            
                            <div className="property-card__address">
                                <span className="dashicons dashicons-location"></span>
                                {virginia.address}
                            </div>
                            
                            <button className="property-card__button">
                                View Community
                            </button>
                        </div>
                    )}
                    
                    {/* Maryland Property Card (Hard-coded) */}
                    {maryland && (
                        <div className="property-card">
                            <div className="property-card__image-container">
                                <div className="property-card__featured-img-mask">
                                    <img 
                                        src={maryland.featured_image} 
                                        className="property-card__featured-img" 
                                        alt={maryland.name} 
                                    />
                                </div>
                                <div className="property-card__label">{maryland.community_label}</div>
                                <img 
                                    className="property-card__badge"
                                    src={maryland.badge}
                                    alt="Award Badge"
                                />
                            </div>
                            
                            <div className="property-card__price">
                                Priced from: {maryland.price}
                            </div>
                            
                            <h3 className="property-card__title">{maryland.name}</h3>
                            
                            <div className="property-card__excerpt">
                                {maryland.excerpt}
                            </div>
                            
                            <div className="property-card__address">
                                <span className="dashicons dashicons-location"></span>
                                {maryland.address}
                            </div>
                            
                            <button className="property-card__button">
                                View Community
                            </button>
                        </div>
                    )}
                </div>
            </div>
        );
    },
    save: () => {
        return null; // Dynamic block, rendering handled by PHP
    },
});