import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, SelectControl, ToggleControl } from '@wordpress/components';
import './editor.scss';

export default function Edit({ attributes, setAttributes }) {
    const { propertyType, showBadge, showPrice, showExcerpt, showAddress } = attributes;
    const blockProps = useBlockProps();

    // This would normally come from an API, but we're using window.integrityProperties
    const property = window.integrityProperties?.[propertyType];
    
    // Create property options array from available properties
    const propertyOptions = [];
    
    if (window.integrityProperties) {
        for (const [key, prop] of Object.entries(window.integrityProperties)) {
            propertyOptions.push({
                label: prop.name,
                value: key
            });
        }
    }
    
    if (!property) {
        return (
            <>
                <InspectorControls>
                    <PanelBody title="Property Settings">
                        <SelectControl
                            label="Property Type"
                            value={propertyType}
                            options={propertyOptions.length > 0 ? propertyOptions : [
                                { label: 'Virginia', value: 'virginia' },
                                { label: 'Maryland', value: 'maryland' },
                            ]}
                            onChange={(value) => setAttributes({ propertyType: value })}
                        />
                    </PanelBody>
                </InspectorControls>
                <div {...blockProps}>Property not found. Please select a valid property type in the sidebar.</div>
            </>
        );
    }

    return (
        <>
            <InspectorControls>
                <PanelBody title="Property Settings" initialOpen={true}>
                    <SelectControl
                        label="Property Type"
                        value={propertyType}
                        options={propertyOptions.length > 0 ? propertyOptions : [
                            { label: 'Virginia', value: 'virginia' },
                            { label: 'Maryland', value: 'maryland' },
                        ]}
                        onChange={(value) => setAttributes({ propertyType: value })}
                    />
                    <ToggleControl
                        label="Show Badge"
                        checked={showBadge}
                        onChange={() => setAttributes({ showBadge: !showBadge })}
                    />
                    <ToggleControl
                        label="Show Price"
                        checked={showPrice}
                        onChange={() => setAttributes({ showPrice: !showPrice })}
                    />
                    <ToggleControl
                        label="Show Excerpt"
                        checked={showExcerpt}
                        onChange={() => setAttributes({ showExcerpt: !showExcerpt })}
                    />
                    <ToggleControl
                        label="Show Address"
                        checked={showAddress}
                        onChange={() => setAttributes({ showAddress: !showAddress })}
                    />
                </PanelBody>
            </InspectorControls>

            <div {...blockProps} className="property-card">
                <div className="property-card__image-container">
                    <div className="property-card__featured-img-mask">
                        <img src={property.featured_image} className="property-card__featured-img" alt={property.name} />
                    </div>
                    <div className="property-card__label">{property.community_label}</div>
                    {showBadge && (
                        <img 
                            className="property-card__badge"
                            src={property.badge}
                            alt="Award Badge"
                        />
                    )}
                </div>
                
                {showPrice && (
                    <div className="property-card__price">
                        Priced from: {property.price}
                    </div>
                )}

                <h3 className="property-card__title">{property.name}</h3>

                {showExcerpt && (
                    <div className="property-card__excerpt">
                        {property.excerpt}
                    </div>
                )}

                {showAddress && (
                    <div className="property-card__address">
                        <span className="dashicons dashicons-location"></span>
                        {property.address}
                    </div>
                )}

                {/* Button with no actual navigation in editor */}
                <button 
                    className="property-card__button"
                    onClick={(e) => e.preventDefault()}
                >
                    View Community
                </button>
            </div>
        </>
    );
}