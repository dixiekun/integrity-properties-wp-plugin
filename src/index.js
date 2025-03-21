/**
 * WordPress dependencies
 */
import { registerBlockType } from '@wordpress/blocks';

/**
 * Internal dependencies
 */
import './index.css';
import PropertyCardEdit from './edit';
import propertyCardMetadata from '../blocks/property-card/block.json';

// Also register the property section block
import '../blocks/property-section/index.js';

registerBlockType(propertyCardMetadata.name, {
    edit: PropertyCardEdit,
    save: () => null, // Dynamic block, render is handled on the server
});