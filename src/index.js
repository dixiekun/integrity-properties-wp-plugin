import { registerBlockType } from '@wordpress/blocks';
import PropertyCardEdit from './edit';
import propertyCardMetadata from '../blocks/property-card/block.json';
import './style.scss';
import './editor.scss';

// Import the property section block
import '../blocks/property-section/index.js';

registerBlockType(propertyCardMetadata.name, {
    edit: PropertyCardEdit,
    save: () => null,
});