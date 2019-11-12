/**
 * Internal block libraries
 */
const { __ } = wp.i18n;
const { registerBlockType, InspectorControls } = wp.blocks;

import edit from './edit';

registerBlockType("horttcore/staff", {
    title: __("Staff", "custom-post-type-staff"),
    description: __("Shows a custom staff loop", "custom-post-type-staff"),
    icon: 'groups',
    category: "widgets",
    keywords: [
        __("Client", "custom-post-type-staff"),
        __("Query", "custom-post-type-staff"),
        __("Loop", "custom-post-type-staff")
    ],
    attributes: {
        order: {
            type: 'string',
            default: 'asc',
        },
        orderBy: {
            type: 'string',
            default: 'menu_order',
        },
        postsToShow: {
            type: 'number',
            default: 10,
        },
        staffId: {
            type: 'string',
        },
    },
    supports: {
        anchor: true,
    },
    edit,
    save: props => {
        return null;
    }
});
