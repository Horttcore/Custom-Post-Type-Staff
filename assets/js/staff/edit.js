/**
 * External dependencies
 */
const { __ } = wp.i18n;

/**
 * WordPress dependencies
 */
const { Component, Fragment } = wp.element;
const {
    PanelBody,
    Placeholder,
    QueryControls,
    Spinner,
} = wp.components;
const {
    InspectorControls,
} = wp.editor;
const { withSelect } = wp.data;
const { createHooks } = wp.hooks;

class Staff extends Component {


    constructor() {
        super(...arguments);
    }


    render() {
        const { attributes, setAttributes, staffPosts, isSelected } = this.props;
        const { order, orderBy, postsToShow } = attributes;
        const hasPosts = Array.isArray(staffPosts) && staffPosts.length;
        const displayPosts = staffPosts;
        const hooks = createHooks();
        
        return (
            <Fragment>
                <InspectorControls>
                    <PanelBody title={__('Staff')}>
                        <QueryControls
                            {...{ order, orderBy }}
                            numberOfItems={postsToShow}
                            onOrderChange={(value) => setAttributes({ order: value })}
                            onOrderByChange={(value) => setAttributes({ orderBy: value })}
                            onNumberOfItemsChange={(value) => setAttributes({ postsToShow: value })}
                        />
                    </PanelBody>
                </InspectorControls>
                <section className="staff">
                    <div className="posts posts--staff">
                        {hasPosts ? (displayPosts.map((post, i) =>
                            hooks.applyFilters('staff-block-editor', (
                                <aside className="post-{post.id} staff type-staff status-{post.status} hentry">
                                    {post._embedded ? (
                                        <figure className="staff__image">
                                            <img src={post._embedded["wp:featuredmedia"][0].media_details.sizes.thumbnail.source_url} alt={post.title.rendered} />
                                        </figure>
                                    ) : ('')}
                                    <div className="staff__wrapper">
                                        <header className="staff__header">
                                            <h1 className="staff__title">{post.title.rendered}</h1>
                                        </header>
                                    </div>
                                </aside>
                            ), post, i)
                        )) : (
                            <Placeholder icon="admin-post" label={__('Staff', 'custom-post-type-staff')}>
                                {!Array.isArray(staffPosts) ?
                                    <Spinner /> :
                                    __('No staff found.')
                                }
                            </Placeholder>
                        )}
                    </div>
                </section>
            </Fragment>
        );
    }
}


export default withSelect((select, props) => {
    const { postsToShow, order, orderBy } = props.attributes;
    const { getEntityRecords } = select('core');
    const staffQuery = {
        orderby: orderBy,
        order: order,
        per_page: postsToShow,
        _embed: true,
    };
    return {
        staffPosts: getEntityRecords('postType', 'staff', staffQuery),
    };
})(Staff);
