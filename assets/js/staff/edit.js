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
    SelectControl,
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
        const { attributes, setAttributes, staffPosts, staffPost, isSelected, staff } = this.props;
        const { order, orderBy, postsToShow, staffId } = attributes;
        const hooks = createHooks();

        let staffSelect = [{
            value: '',
            label: __('Select')
        }];

        let loopPosts = staffPosts;

        if (staff) {
            staff.map((item) => {
                staffSelect.push({ value: item.id, label: item.title.rendered })
            })
        }

        if (staffId) {
            loopPosts = [staffPost];
        }

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
                        {staff && <SelectControl
                            label={__('Select contact person:')}
                            value={staffId}
                            onChange={(staffId) => { setAttributes({ staffId }) }}
                            options={staffSelect}
                        />}
                    </PanelBody>
                </InspectorControls>
                <section className="staff">
                    <div className="posts posts--staff">
                        {loopPosts ? (loopPosts.map((post, i) => {
                            if (!post) return;


                            return hooks.applyFilters('staff-block-editor', (
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
                        }
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
    const { postsToShow, order, orderBy, staffId } = props.attributes;
    const { getEntityRecords, getEntityRecord } = select('core');

    let staffPosts = getEntityRecords('postType', 'staff', {
        orderby: orderBy,
        order: order,
        per_page: postsToShow,
        _embed: true,
    });

    let staffPost = null;

    if (staffId) {
        staffPost = getEntityRecord('postType', 'staff', staffId);
    }

    return {
        staffPosts,
        staffPost,
        staff: getEntityRecords('postType', 'staff', {
            orderby: 'title',
            order: 'asc',
            per_page: 100,
        }),
    };
})(Staff);