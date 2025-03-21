import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, SelectControl, ToggleControl } from '@wordpress/components';

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

            <div {...blockProps} className="property-card w-full max-w-md bg-white rounded-2xl shadow-md border border-gray-100 pb-2 transition-transform hover:-translate-y-1 hover:shadow-lg">
                <div className="relative">
                    <div className="overflow-hidden rounded-t-2xl">
                        <img src={property.featured_image} className="w-full object-cover" alt={property.name} />
                    </div>
                    <div className="absolute top-4 left-0 bg-primary py-2 px-4 text-white text-sm font-medium">
                        {property.community_label}
                    </div>
                    {showBadge && (
                        <img 
                            className="absolute -bottom-5 -right-8 w-40 h-40"
                            src={property.badge}
                            alt="Award Badge"
                        />
                    )}
                </div>
                
                {showPrice && (
                    <div className="text-primary font-medium mx-4 mt-4 mb-2">
                        Priced from: {property.price}
                    </div>
                )}

                <h3 className="mx-4 mb-2 text-2xl font-semibold">{property.name}</h3>

                {showExcerpt && (
                    <div className="mx-4 mb-4 text-gray-600 text-sm leading-relaxed">
                        {property.excerpt}
                    </div>
                )}

                {showAddress && (
                    <div className="flex items-center gap-2 mx-4 mb-4 text-gray-600 text-sm">
                        <span className="dashicons dashicons-location text-secondary"></span>
                        {property.address}
                    </div>
                )}

                {/* Button with no actual navigation in editor */}
                <button 
                    className="block mx-4 mb-4 py-3 px-4 bg-secondary text-white font-medium rounded transition-colors hover:bg-primary text-center"
                    onClick={(e) => e.preventDefault()}
                >
                    View Community
                </button>
            </div>
        </>
    );
}