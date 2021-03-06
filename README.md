# Custom Post Type Staff

A custom post type to manage staff

## Supports

* Title
* Editor
* Thumbnail
* Page attributes
* Divison ( Taxonomy )

## Custom Fields

* Salutation
* Grade
* First name
* Last name
* Role
* Phone
* Fax
* Mobil
* E-Mail
* URL
* Room

## Language Support

* english
* german

## Hooks

### Actions

* `staff-meta-table-before` Before the staff meta table
* `staff-meta-before` First row in the staff meta table
* `staff-meta-after` Last row in the staff meta table
* `staff-meta-table-after` After the staff meta table

### Filters

* `staff-meta` Staff meta is past as array
* `save-staff-meta` Staff meta is past as array

## Template tags

`get_staff_meta ( str $key [, int $post_id] )`
`the_staff_meta ( str $key [, int $post_id] )`
`hast_staff_meta ( str $key [, int $post_id] )`

## Changelog

### v2.0.2

* Fixed: `Replaced sanitize_url` with `esc_url_raw`
* Fixed: Salutation warning on `save_post`

### v2.0.1

* Template tags fixed
* Added `has_staff_meta` template tag
* Added `room` field
* Added `url` field

### v2.0

* Refactoring
* Added template tag `get_staff_meta`
* Added template tag `the_staff_meta`

### v1.1.2

* Enhancement: Cleanup

### v1.1.1

* Add filter `save-staff-meta`

### v1.1

* Added gender
* Added first name
* Added last name
* Cleanup

### v1.0

* Initial release

