/**
 * @license Copyright (c) 2003-2015, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function(config) {
    // Define changes to default configuration here. For example:
    // config.language = 'fr';
    // config.uiColor = '#AADC6E';
    // Define changes to default configuration here. For example:
    config.language = 'th';
    //config.uiColor = '#AADC6E';

    config.extraPlugins = 'image';
    config.filebrowserUploadUrl = '../action/ckupload.php?method=upload';
    config.image_removeLinkByEmptyURL = true;
    config.image_previewText = CKEDITOR.tools.repeat('ตัวอย่างรูปภาพ ', 100);
};
/**
 * @license Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

