import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps, RichText } from '@wordpress/block-editor';

// Removed SCSS import

registerBlockType('integrity/property-section', {
    edit: ({ attributes, setAttributes }) => {
        const blockProps = useBlockProps({
            className: 'w-full max-w-5xl mx-auto p-8 bg-primary-50 rounded-2xl shadow-sm',
        });
        
        // Access property data from global object
        const virginia = window.integrityProperties?.virginia;
        const maryland = window.integrityProperties?.maryland;
        
        return (
            <div {...blockProps}>
                <div className="text-center max-w-3xl mx-auto mb-12 px-4">
                    <RichText
                        tagName="h2"
                        className="text-4xl font-bold mb-6 text-gray-800"
                        value={attributes.title}
                        onChange={(title) => setAttributes({ title })}
                        placeholder="Section Title"
                    />
                    <RichText
                        tagName="p"
                        className="text-lg leading-relaxed text-gray-600"
                        value={attributes.description}
                        onChange={(description) => setAttributes({ description })}
                        placeholder="Section Description"
                    />
                </div>
                
                <div className="grid grid-cols-1 md:grid-cols-2 gap-8 p-4 justify-items-center min-h-[200px]">
                    {/* Virginia Property Card (Hard-coded) */}
                    {virginia && (
                        <div className="w-full max-w-md bg-white rounded-2xl shadow-md border border-gray-100 pb-2 transition-transform hover:-translate-y-1 hover:shadow-lg">
                            <div className="relative">
                                <div className="overflow-hidden rounded-t-2xl">
                                    <img 
                                        src={virginia.featured_image} 
                                        className="w-full object-cover" 
                                        alt={virginia.name} 
                                    />
                                </div>
                                <div className="absolute top-4 left-0 bg-primary py-2 px-4 text-white text-sm font-medium">
                                    {virginia.community_label}
                                </div>
                                <img 
                                    className="absolute -bottom-5 -right-8 w-40 h-40"
                                    src={virginia.badge}
                                    alt="Award Badge"
                                />
                            </div>
                            
                            <div className="text-primary font-medium mx-4 mt-4 mb-2">
                                Priced from: {virginia.price}
                            </div>
                            
                            <h3 className="mx-4 mb-2 text-2xl font-semibold">{virginia.name}</h3>
                            
                            <div className="mx-4 mb-4 text-gray-600 text-sm leading-relaxed">
                                {virginia.excerpt}
                            </div>
                            
                            <div className="flex items-center gap-2 mx-4 mb-4 text-gray-600 text-sm">
                                <span className="dashicons dashicons-location text-secondary"></span>
                                {virginia.address}
                            </div>
                            
                            <button className="block mx-4 mb-4 py-3 px-4 bg-secondary text-white font-medium rounded transition-colors hover:bg-primary text-center">
                                View Community
                            </button>
                        </div>
                    )}
                    
                    {/* Maryland Property Card (Hard-coded) */}
                    {maryland && (
                        <div className="w-full max-w-md bg-white rounded-2xl shadow-md border border-gray-100 pb-2 transition-transform hover:-translate-y-1 hover:shadow-lg">
                            <div className="relative">
                                <div className="overflow-hidden rounded-t-2xl">
                                    <img 
                                        src={maryland.featured_image} 
                                        className="w-full object-cover" 
                                        alt={maryland.name} 
                                    />
                                </div>
                                <div className="absolute top-4 left-0 bg-primary py-2 px-4 text-white text-sm font-medium">
                                    {maryland.community_label}
                                </div>
                                <img 
                                    className="absolute -bottom-5 -right-8 w-40 h-40"
                                    src={maryland.badge}
                                    alt="Award Badge"
                                />
                            </div>
                            
                            <div className="text-primary font-medium mx-4 mt-4 mb-2">
                                Priced from: {maryland.price}
                            </div>
                            
                            <h3 className="mx-4 mb-2 text-2xl font-semibold">{maryland.name}</h3>
                            
                            <div className="mx-4 mb-4 text-gray-600 text-sm leading-relaxed">
                                {maryland.excerpt}
                            </div>
                            
                            <div className="flex items-center gap-2 mx-4 mb-4 text-gray-600 text-sm">
                                <span className="dashicons dashicons-location text-secondary"></span>
                                {maryland.address}
                            </div>
                            
                            <button className="block mx-4 mb-4 py-3 px-4 bg-secondary text-white font-medium rounded transition-colors hover:bg-primary text-center">
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