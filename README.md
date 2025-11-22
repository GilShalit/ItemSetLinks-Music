# Item Set Links Module for Omeka S

A custom Omeka S module that adds a block for displaying links to item sets with automatic translation support.

## Features

- ✅ Adds "Item Set Links" block to your page editor
- ✅ Automatic translation support - displays item set titles in the current locale
- ✅ Configurable item set IDs directly in the block editor
- ✅ Optional description text
- ✅ Generates correct browse URLs for each item set
- ✅ Uses your existing CSS classes (home-description, home-link-container, etc.)
- ✅ Clean, maintainable code

## Requirements

- Omeka S 4.0.0 or higher

## Installation

1. **Download the module**
   - Extract the `ItemSetLinks` folder

2. **Upload to Omeka S**
   - Upload the entire `ItemSetLinks` folder to your Omeka S installation's `modules` directory
   - The path should be: `modules/ItemSetLinks/`

3. **Install the module**
   - Go to your Omeka S admin panel
   - Navigate to: **Modules** (in the left sidebar)
   - Find "Item Set Links" in the list
   - Click **Install**
   - The module will be automatically activated

## Usage

### Adding the Block to a Page

1. Go to your site's page editor
2. Click **"Add new block"** (the panel will open)
3. Look for and click **"Item Set Links"** in the list of available blocks
4. The block will be added to your page

### Configuring the Block

When you add the block, you'll see two configuration fields:

#### 1. Item Set IDs (Required)
Enter the IDs of the item sets you want to display, separated by commas.

**Example:**
```
1303, 866, 605, 693, 1372, 558
```

**To find Item Set IDs:**
- Go to admin panel → Item Sets
- Click on an item set
- Look at the URL: `admin/item-set/123/edit` - the number `123` is the ID

#### 2. Description (Optional)
Enter a description text that will appear above the item set links.

**Example:**
```
Database of "Music, Muslims and Jews", here you will find all the source materials and research outcomes of this project.
```

Leave this field empty if you don't want a description.

### Saving and Publishing

1. Click **"Add"** to add the block to your page
2. You can reorder blocks by dragging them
3. Click **"Save"** to save the page

## How Translation Works

The module automatically displays item set titles in the current page locale. Here's how to set up translations:

### Adding Translations to Item Sets

1. Go to admin panel → **Item Sets**
2. Click **Edit** on an item set
3. For the **Title** field, add translations:
   - Click the language selector next to the title field
   - Add the title in each language you support (English, Hebrew, Arabic, etc.)
4. **Save** the item set
5. Repeat for all item sets

**Example:**
- English: "Recordings"
- Hebrew: "הקלטות"
- Arabic: "التسجيلات"

When a user visits your page in Hebrew, they'll see "הקלטות". When they visit in English, they'll see "Recordings".

## Styling

The module uses the following CSS classes (which match your original HTML):

```css
.home-description       /* Container for the description text */
.home-link-container    /* Container for all item set links */
.home-link-item         /* Individual item set link wrapper */
.home-link1             /* First item set link */
.home-link2             /* Second item set link */
.home-link3             /* Third item set link */
/* etc. */
```

Your existing CSS should work without any modifications.

## Customization

### Changing the Block Label

Edit `src/Site/BlockLayout/ItemSetLinks.php`, line 17:
```php
public function getLabel()
{
    return 'Item Set Links'; // Change this text
}
```

### Customizing the HTML Output

Edit `view/common/block-layout/item-set-links.phtml` to change the HTML structure.

### Adding More Configuration Options

You can add more fields to the block configuration by editing the `form()` method in `src/Site/BlockLayout/ItemSetLinks.php`.

## Troubleshooting

### Block doesn't appear in "Add new block" panel
- Verify the module is **installed** and **activated** in admin panel → Modules
- Clear cache: Settings → Global Settings → Clear cache
- Check file permissions on the modules directory

### Item set titles not translating
- Ensure you've added titles in multiple languages to your item sets
- Check that you're viewing the page in a different locale
- Verify the locale switcher is working on your site

### Wrong URLs generated
- Verify the item set IDs are correct
- Check that the item sets exist and are assigned to your site
- Make sure the item sets are public (or you're logged in)

### Description not showing
- Check that you've entered text in the Description field
- The description field accepts plain text only (no HTML)

## Uninstallation

To uninstall the module:

1. Go to admin panel → Modules
2. Find "Item Set Links"
3. Click **Deactivate** (this will not delete your blocks, but they won't display)
4. Click **Uninstall** (this is permanent)
5. Manually delete the `modules/ItemSetLinks` folder if desired

**Note:** Uninstalling will remove the block from all pages where it's used.

## Support

This is a custom module created for a specific use case. For issues or questions:
- Check the Omeka S documentation: https://omeka.org/s/docs/
- Review the code comments in the module files
- Consult the Omeka S forums: https://forum.omeka.org/

## License

GPL-3.0

## Version History

### 1.0.0 (Initial Release)
- Basic item set links block
- Translation support
- Configurable item set IDs
- Optional description field
