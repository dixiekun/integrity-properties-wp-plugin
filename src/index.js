import { registerBlockType } from '@wordpress/blocks';
import Edit from './edit';
import metadata from '../blocks/property-card/block.json';
import './style.scss';  // If you have this file in src directory
import './editor.scss'; // If you have this file in src directory

registerBlockType(metadata.name, {
    edit: Edit,
    save: () => null, // We'll render this dynamically on the PHP side
});