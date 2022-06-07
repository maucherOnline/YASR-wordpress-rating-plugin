/*
 * ATTENTION: The "eval" devtool has been used (maybe by default in mode: "development").
 * This devtool is neither made for production nor for readable output files.
 * It uses "eval()" calls to create a separate source file in the browser devtools.
 * If you are trying to read the output file, select a different devtool (https://webpack.js.org/configuration/devtool/)
 * or disable the default devtool with "devtool: false".
 * If you are looking for production-ready output files, see mode: "production" (https://webpack.js.org/configuration/mode/).
 */
/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./admin/js/src/guten/blocks/noStarsRankings.js":
/*!******************************************************!*\
  !*** ./admin/js/src/guten/blocks/noStarsRankings.js ***!
  \******************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var yasrGutenUtils__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! yasrGutenUtils */ \"./admin/js/src/guten/yasrGutenUtils.js\");\n/* harmony import */ var _includes_blocks_ranking_users_block_json__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../../../../../includes/blocks/ranking-users/block.json */ \"./includes/blocks/ranking-users/block.json\");\n/* harmony import */ var _includes_blocks_ranking_reviewers_block_json__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../../includes/blocks/ranking-reviewers/block.json */ \"./includes/blocks/ranking-reviewers/block.json\");\nvar registerBlockType = wp.blocks.registerBlockType; // Import from wp.blocks\n\nvar PanelBody = wp.components.PanelBody;\nvar Fragment = wp.element.Fragment;\nvar _wp$blockEditor = wp.blockEditor,\n    useBlockProps = _wp$blockEditor.useBlockProps,\n    InspectorControls = _wp$blockEditor.InspectorControls;\n\n\n //Most active users\n\nregisterBlockType(_includes_blocks_ranking_users_block_json__WEBPACK_IMPORTED_MODULE_1__, {\n  edit: function edit(props) {\n    var blockProps = useBlockProps({\n      className: 'yasr-active-users-block'\n    });\n    var YasrTopVisitorSettings = [/*#__PURE__*/React.createElement(yasrGutenUtils__WEBPACK_IMPORTED_MODULE_0__.YasrNoSettingsPanel, {\n      key: 0\n    })];\n    {\n      wp.hooks.doAction('yasr_top_visitor_setting', YasrTopVisitorSettings);\n    }\n\n    function YasrTopVisitorPanel(props) {\n      return /*#__PURE__*/React.createElement(InspectorControls, null, /*#__PURE__*/React.createElement(PanelBody, {\n        title: \"Settings\"\n      }, /*#__PURE__*/React.createElement(\"div\", {\n        className: \"yasr-guten-block-panel\"\n      }, /*#__PURE__*/React.createElement(\"div\", null, YasrTopVisitorSettings))));\n    }\n\n    return /*#__PURE__*/React.createElement(Fragment, null, /*#__PURE__*/React.createElement(YasrTopVisitorPanel, null), /*#__PURE__*/React.createElement(\"div\", blockProps, \"[yasr_most_active_users]\"));\n  },\n\n  /**\n   * The save function defines the way in which the different attributes should be combined\n   * into the final markup, which is then serialized by Gutenberg into post_content.\n   *\n   * The \"save\" property must be specified and must be a valid function.\n   *\n   * @link https://wordpress.org/gutenberg/handbook/block-api/block-edit-save/\n   */\n  save: function save(props) {\n    var blockProps = useBlockProps.save({\n      className: 'yasr-active-users-block'\n    });\n    return /*#__PURE__*/React.createElement(\"div\", blockProps, \"[yasr_most_active_users]\");\n  }\n}); //Most Active reviewers\n\nregisterBlockType(_includes_blocks_ranking_reviewers_block_json__WEBPACK_IMPORTED_MODULE_2__, {\n  edit: function edit(props) {\n    var blockProps = useBlockProps({\n      className: 'yasr-reviewers-block'\n    });\n    var YasrTopReviewersSettings = [/*#__PURE__*/React.createElement(yasrGutenUtils__WEBPACK_IMPORTED_MODULE_0__.YasrNoSettingsPanel, {\n      key: 0\n    })];\n    {\n      wp.hooks.doAction('yasr_top_reviewers_setting', YasrTopReviewersSettings);\n    }\n\n    function YasrTopReviewersPanel(props) {\n      return /*#__PURE__*/React.createElement(InspectorControls, null, /*#__PURE__*/React.createElement(PanelBody, {\n        title: \"Settings\"\n      }, /*#__PURE__*/React.createElement(\"div\", {\n        className: \"yasr-guten-block-panel\"\n      }, /*#__PURE__*/React.createElement(\"div\", null, YasrTopReviewersSettings))));\n    }\n\n    return /*#__PURE__*/React.createElement(Fragment, null, /*#__PURE__*/React.createElement(YasrTopReviewersPanel, null), /*#__PURE__*/React.createElement(\"div\", blockProps, \"[yasr_top_reviewers]\"));\n  },\n\n  /**\n   * The save function defines the way in which the different attributes should be combined\n   * into the final markup, which is then serialized by Gutenberg into post_content.\n   *\n   * The \"save\" property must be specified and must be a valid function.\n   *\n   * @link https://wordpress.org/gutenberg/handbook/block-api/block-edit-save/\n   */\n  save: function save(props) {\n    var blockProps = useBlockProps.save({\n      className: 'yasr-reviewers-block'\n    });\n    return /*#__PURE__*/React.createElement(\"div\", blockProps, \"[yasr_top_reviewers]\");\n  }\n});\n\n//# sourceURL=webpack://yet-another-stars-rating/./admin/js/src/guten/blocks/noStarsRankings.js?");

/***/ }),

/***/ "./admin/js/src/guten/blocks/overallRating.js":
/*!****************************************************!*\
  !*** ./admin/js/src/guten/blocks/overallRating.js ***!
  \****************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _includes_blocks_overall_rating_block_json__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../../../../includes/blocks/overall-rating/block.json */ \"./includes/blocks/overall-rating/block.json\");\n/* harmony import */ var _registerBlockTypeEdit__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../registerBlockTypeEdit */ \"./admin/js/src/guten/registerBlockTypeEdit.js\");\nvar registerBlockType = wp.blocks.registerBlockType; // Import from wp.blocks\n\nvar useBlockProps = wp.blockEditor.useBlockProps;\n\n\n/**\n * Register: a Gutenberg Block.\n *\n * Registers a new block provided a unique name and an object defining its\n * behavior. Once registered, the block is made editor as an option to any\n * editor interface where blocks are implemented.\n *\n * @link https://wordpress.org/gutenberg/handbook/block-api/\n * @param  {string}   name     Block name.\n * @param  {Object}   settings Block settings.\n * @return {?WPBlock}          The block, if it has been successfully\n *                             registered; otherwise `undefined`.\n */\n\nregisterBlockType(_includes_blocks_overall_rating_block_json__WEBPACK_IMPORTED_MODULE_0__, {\n  edit: _registerBlockTypeEdit__WEBPACK_IMPORTED_MODULE_1__[\"default\"],\n\n  /**\n   * The save function defines the way in which the different attributes should be combined\n   * into the final markup, which is then serialized by Gutenberg into post_content.\n   *\n   * The \"save\" property must be specified and must be a valid function.\n   *\n   * @link https://wordpress.org/gutenberg/handbook/block-api/block-edit-save/\n   */\n  save: function save(props) {\n    var blockProps = useBlockProps.save({\n      className: 'yasr-overall-block'\n    });\n    var _props$attributes = props.attributes,\n        size = _props$attributes.size,\n        postId = _props$attributes.postId;\n    var yasrOverallAttributes = '';\n    var post_id = postId;\n\n    if (size) {\n      yasrOverallAttributes += 'size=\"' + size + '\"';\n    }\n\n    if (postId) {\n      yasrOverallAttributes += ' postid=\"' + post_id + '\"';\n    }\n\n    return /*#__PURE__*/React.createElement(\"div\", blockProps, \"[yasr_overall_rating \", yasrOverallAttributes, \"]\");\n  }\n});\n\n//# sourceURL=webpack://yet-another-stars-rating/./admin/js/src/guten/blocks/overallRating.js?");

/***/ }),

/***/ "./admin/js/src/guten/blocks/rankings.js":
/*!***********************************************!*\
  !*** ./admin/js/src/guten/blocks/rankings.js ***!
  \***********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var yasrGutenUtils__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! yasrGutenUtils */ \"./admin/js/src/guten/yasrGutenUtils.js\");\n/* harmony import */ var _includes_blocks_ranking_overall_rating_block_json__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../../../../../includes/blocks/ranking-overall-rating/block.json */ \"./includes/blocks/ranking-overall-rating/block.json\");\n/* harmony import */ var _includes_blocks_ranking_visitor_votes_block_json__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../../includes/blocks/ranking-visitor-votes/block.json */ \"./includes/blocks/ranking-visitor-votes/block.json\");\nvar registerBlockType = wp.blocks.registerBlockType; // Import from wp.blocks\n\nvar PanelBody = wp.components.PanelBody;\nvar Fragment = wp.element.Fragment;\nvar _wp$blockEditor = wp.blockEditor,\n    useBlockProps = _wp$blockEditor.useBlockProps,\n    InspectorControls = _wp$blockEditor.InspectorControls;\n\n\n\nregisterBlockType(_includes_blocks_ranking_overall_rating_block_json__WEBPACK_IMPORTED_MODULE_1__, {\n  edit: function edit(props) {\n    var blockProps = useBlockProps({\n      className: 'yasr-ov-ranking-block'\n    });\n    var YasrORRSettings = [/*#__PURE__*/React.createElement(yasrGutenUtils__WEBPACK_IMPORTED_MODULE_0__.YasrNoSettingsPanel, {\n      key: 0\n    })];\n    {\n      wp.hooks.doAction('yasr_overall_rating_rankings', YasrORRSettings);\n    }\n\n    function YasrORRPanel(props) {\n      return /*#__PURE__*/React.createElement(InspectorControls, null, /*#__PURE__*/React.createElement(PanelBody, {\n        title: \"Settings\"\n      }, /*#__PURE__*/React.createElement(\"div\", {\n        className: \"yasr-guten-block-panel\"\n      }, /*#__PURE__*/React.createElement(\"div\", null, YasrORRSettings))));\n    }\n\n    return /*#__PURE__*/React.createElement(Fragment, null, /*#__PURE__*/React.createElement(YasrORRPanel, null), /*#__PURE__*/React.createElement(\"div\", blockProps, \"[yasr_ov_ranking]\"));\n  },\n\n  /**\n   * The save function defines the way in which the different attributes should be combined\n   * into the final markup, which is then serialized by Gutenberg into post_content.\n   *\n   * The \"save\" property must be specified and must be a valid function.\n   *\n   * @link https://wordpress.org/gutenberg/handbook/block-api/block-edit-save/\n   */\n  save: function save(props) {\n    var blockProps = useBlockProps.save({\n      className: 'yasr-ov-ranking-block'\n    });\n    return /*#__PURE__*/React.createElement(\"div\", blockProps, \"[yasr_ov_ranking]\");\n  }\n});\nregisterBlockType(_includes_blocks_ranking_visitor_votes_block_json__WEBPACK_IMPORTED_MODULE_2__, {\n  edit: function edit(props) {\n    var blockProps = useBlockProps({\n      className: 'yasr-vv-ranking-block'\n    });\n    var YasrVVRSettings = [/*#__PURE__*/React.createElement(yasrGutenUtils__WEBPACK_IMPORTED_MODULE_0__.YasrNoSettingsPanel, {\n      key: 0\n    })];\n    {\n      wp.hooks.doAction('yasr_visitor_votes_rankings', YasrVVRSettings);\n    }\n\n    function YasrVVRPanel(props) {\n      return /*#__PURE__*/React.createElement(InspectorControls, null, /*#__PURE__*/React.createElement(PanelBody, {\n        title: \"Settings\"\n      }, /*#__PURE__*/React.createElement(\"div\", {\n        className: \"yasr-guten-block-panel\"\n      }, /*#__PURE__*/React.createElement(\"div\", null, YasrVVRSettings))));\n    }\n\n    return /*#__PURE__*/React.createElement(Fragment, null, /*#__PURE__*/React.createElement(YasrVVRPanel, null), /*#__PURE__*/React.createElement(\"div\", blockProps, \"[yasr_most_or_highest_rated_posts]\"));\n  },\n\n  /**\n   * The save function defines the way in which the different attributes should be combined\n   * into the final markup, which is then serialized by Gutenberg into post_content.\n   *\n   * The \"save\" property must be specified and must be a valid function.\n   *\n   * @link https://wordpress.org/gutenberg/handbook/block-api/block-edit-save/\n   */\n  save: function save(props) {\n    var blockProps = useBlockProps.save({\n      className: 'yasr-vv-ranking-block'\n    });\n    return /*#__PURE__*/React.createElement(\"div\", blockProps, \"[yasr_most_or_highest_rated_posts]\");\n  }\n});\n\n//# sourceURL=webpack://yet-another-stars-rating/./admin/js/src/guten/blocks/rankings.js?");

/***/ }),

/***/ "./admin/js/src/guten/blocks/userRateHistory.js":
/*!******************************************************!*\
  !*** ./admin/js/src/guten/blocks/userRateHistory.js ***!
  \******************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var yasrGutenUtils__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! yasrGutenUtils */ \"./admin/js/src/guten/yasrGutenUtils.js\");\n/* harmony import */ var _includes_blocks_user_rate_history_block_json__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../../../../../includes/blocks/user-rate-history/block.json */ \"./includes/blocks/user-rate-history/block.json\");\nvar registerBlockType = wp.blocks.registerBlockType; // Import from wp.blocks\n\nvar PanelBody = wp.components.PanelBody;\nvar Fragment = wp.element.Fragment;\nvar _wp$blockEditor = wp.blockEditor,\n    useBlockProps = _wp$blockEditor.useBlockProps,\n    InspectorControls = _wp$blockEditor.InspectorControls;\n\n //Most active users\n\nregisterBlockType(_includes_blocks_user_rate_history_block_json__WEBPACK_IMPORTED_MODULE_1__, {\n  edit: function edit(props) {\n    var blockProps = useBlockProps({\n      className: 'yasr-user-rate-history'\n    });\n    var YasrUserRateHisotrySettings = [/*#__PURE__*/React.createElement(yasrGutenUtils__WEBPACK_IMPORTED_MODULE_0__.YasrNoSettingsPanel, {\n      key: 0\n    })];\n    {\n      wp.hooks.doAction('yasr_user_rate_history_settings', YasrUserRateHisotrySettings);\n    }\n\n    function YasrUserRateHistoryPanel(props) {\n      return /*#__PURE__*/React.createElement(InspectorControls, null, /*#__PURE__*/React.createElement(PanelBody, {\n        title: \"Settings\"\n      }, /*#__PURE__*/React.createElement(\"div\", {\n        className: \"yasr-guten-block-panel\"\n      }, /*#__PURE__*/React.createElement(\"div\", null, YasrUserRateHisotrySettings))));\n    }\n\n    return /*#__PURE__*/React.createElement(Fragment, null, /*#__PURE__*/React.createElement(\"div\", blockProps, \"[yasr_user_rate_history]\"));\n  },\n\n  /**\n   * The save function defines the way in which the different attributes should be combined\n   * into the final markup, which is then serialized by Gutenberg into post_content.\n   *\n   * The \"save\" property must be specified and must be a valid function.\n   *\n   * @link https://wordpress.org/gutenberg/handbook/block-api/block-edit-save/\n   */\n  save: function save(props) {\n    var blockProps = useBlockProps.save({\n      className: 'yasr-user-rate-history'\n    });\n    return /*#__PURE__*/React.createElement(\"div\", blockProps, \"[yasr_user_rate_history]\");\n  }\n});\n\n//# sourceURL=webpack://yet-another-stars-rating/./admin/js/src/guten/blocks/userRateHistory.js?");

/***/ }),

/***/ "./admin/js/src/guten/blocks/visitorVotes.js":
/*!***************************************************!*\
  !*** ./admin/js/src/guten/blocks/visitorVotes.js ***!
  \***************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _registerBlockTypeEdit__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../registerBlockTypeEdit */ \"./admin/js/src/guten/registerBlockTypeEdit.js\");\n/* harmony import */ var _includes_blocks_visitor_votes_block_json__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../../../../../includes/blocks/visitor-votes/block.json */ \"./includes/blocks/visitor-votes/block.json\");\nvar registerBlockType = wp.blocks.registerBlockType; // Import from wp.blocks\n\nvar useBlockProps = wp.blockEditor.useBlockProps;\n\n\nregisterBlockType(_includes_blocks_visitor_votes_block_json__WEBPACK_IMPORTED_MODULE_1__, {\n  edit: _registerBlockTypeEdit__WEBPACK_IMPORTED_MODULE_0__[\"default\"],\n\n  /**\n   * The save function defines the way in which the different attributes should be combined\n   * into the final markup, which is then serialized by Gutenberg into post_content.\n   *\n   * The \"save\" property must be specified and must be a valid function.\n   *\n   * @link https://wordpress.org/gutenberg/handbook/block-api/block-edit-save/\n   */\n  save: function save(props) {\n    var blockProps = useBlockProps.save({\n      className: 'yasr-vv-block'\n    });\n    var _props$attributes = props.attributes,\n        size = _props$attributes.size,\n        postId = _props$attributes.postId;\n    var yasrVVAttributes = '';\n\n    if (size) {\n      yasrVVAttributes += 'size=\"' + size + '\"';\n    }\n\n    if (postId) {\n      yasrVVAttributes += ' postid=\"' + postId + '\"';\n    }\n\n    return /*#__PURE__*/React.createElement(\"div\", blockProps, \"[yasr_visitor_votes \", yasrVVAttributes, \"]\");\n  }\n});\n\n//# sourceURL=webpack://yet-another-stars-rating/./admin/js/src/guten/blocks/visitorVotes.js?");

/***/ }),

/***/ "./admin/js/src/guten/registerBlockTypeEdit.js":
/*!*****************************************************!*\
  !*** ./admin/js/src/guten/registerBlockTypeEdit.js ***!
  \*****************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export */ __webpack_require__.d(__webpack_exports__, {\n/* harmony export */   \"default\": () => (__WEBPACK_DEFAULT_EXPORT__)\n/* harmony export */ });\n/* harmony import */ var yasrGutenUtils__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! yasrGutenUtils */ \"./admin/js/src/guten/yasrGutenUtils.js\");\nvar Fragment = wp.element.Fragment;\nvar useBlockProps = wp.blockEditor.useBlockProps;\n\n/**\n * Return the edit Function to be used in registerBlockType\n *\n * @param props\n * @returns {JSX.Element}\n */\n\nvar yasrEditFunction = function yasrEditFunction(props) {\n  var _props$attributes = props.attributes,\n      size = _props$attributes.size,\n      postId = _props$attributes.postId,\n      name = props.name,\n      isSelected = props.isSelected,\n      setAttributes = props.setAttributes;\n\n  var _YasrSetBlockAttribut = (0,yasrGutenUtils__WEBPACK_IMPORTED_MODULE_0__.YasrSetBlockAttributes)(name),\n      className = _YasrSetBlockAttribut.className,\n      shortCode = _YasrSetBlockAttribut.shortCode;\n\n  var panelAttributes = {\n    block: name,\n    size: size,\n    postId: postId,\n    setAttributes: setAttributes\n  };\n  var blockProps = useBlockProps({\n    className: className\n  });\n  var sizeAttribute = (0,yasrGutenUtils__WEBPACK_IMPORTED_MODULE_0__.YasrBlockSizeAttribute)(size);\n  var postIdAttribute = (0,yasrGutenUtils__WEBPACK_IMPORTED_MODULE_0__.YasrBlockPostidAttribute)(postId);\n  return /*#__PURE__*/React.createElement(Fragment, null, isSelected && /*#__PURE__*/React.createElement(yasrGutenUtils__WEBPACK_IMPORTED_MODULE_0__.YasrBlocksPanel, panelAttributes), /*#__PURE__*/React.createElement(\"div\", blockProps, \"[\", shortCode, sizeAttribute, postIdAttribute, \"]\", isSelected && /*#__PURE__*/React.createElement(yasrGutenUtils__WEBPACK_IMPORTED_MODULE_0__.YasrPrintSelectSize, {\n    size: size,\n    setAttributes: setAttributes\n  })));\n};\n\n/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (yasrEditFunction);\n\n//# sourceURL=webpack://yet-another-stars-rating/./admin/js/src/guten/registerBlockTypeEdit.js?");

/***/ }),

/***/ "./admin/js/src/guten/yasrGutenUtils.js":
/*!**********************************************!*\
  !*** ./admin/js/src/guten/yasrGutenUtils.js ***!
  \**********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export */ __webpack_require__.d(__webpack_exports__, {\n/* harmony export */   \"YasrBlockPostidAttribute\": () => (/* binding */ YasrBlockPostidAttribute),\n/* harmony export */   \"YasrBlockSizeAttribute\": () => (/* binding */ YasrBlockSizeAttribute),\n/* harmony export */   \"YasrBlocksPanel\": () => (/* binding */ YasrBlocksPanel),\n/* harmony export */   \"YasrDivRatingOverall\": () => (/* binding */ YasrDivRatingOverall),\n/* harmony export */   \"YasrNoSettingsPanel\": () => (/* binding */ YasrNoSettingsPanel),\n/* harmony export */   \"YasrPrintInputId\": () => (/* binding */ YasrPrintInputId),\n/* harmony export */   \"YasrPrintSelectSize\": () => (/* binding */ YasrPrintSelectSize),\n/* harmony export */   \"YasrProText\": () => (/* binding */ YasrProText),\n/* harmony export */   \"YasrSetBlockAttributes\": () => (/* binding */ YasrSetBlockAttributes),\n/* harmony export */   \"yasrLabelSelectSize\": () => (/* binding */ yasrLabelSelectSize),\n/* harmony export */   \"yasrLeaveThisBlankText\": () => (/* binding */ yasrLeaveThisBlankText),\n/* harmony export */   \"yasrOptionalText\": () => (/* binding */ yasrOptionalText),\n/* harmony export */   \"yasrOverallDescription\": () => (/* binding */ yasrOverallDescription),\n/* harmony export */   \"yasrSelectSizeChoose\": () => (/* binding */ yasrSelectSizeChoose),\n/* harmony export */   \"yasrSelectSizeLarge\": () => (/* binding */ yasrSelectSizeLarge),\n/* harmony export */   \"yasrSelectSizeMedium\": () => (/* binding */ yasrSelectSizeMedium),\n/* harmony export */   \"yasrSelectSizeSmall\": () => (/* binding */ yasrSelectSizeSmall),\n/* harmony export */   \"yasrVisitorVotesDescription\": () => (/* binding */ yasrVisitorVotesDescription)\n/* harmony export */ });\n//setting costants\nvar __ = wp.i18n.__; // Import __() from wp.i18n\n\nvar PanelBody = wp.components.PanelBody;\nvar InspectorControls = wp.blockEditor.InspectorControls;\nvar yasrOptionalText = __('All these settings are optional', 'yet-another-stars-rating');\nvar yasrLabelSelectSize = __('Choose Size', 'yet-another-stars-rating');\nvar yasrSelectSizeChoose = __('Choose stars size', 'yet-another-stars-rating');\nvar yasrSelectSizeSmall = __('Small', 'yet-another-stars-rating');\nvar yasrSelectSizeMedium = __('Medium', 'yet-another-stars-rating');\nvar yasrSelectSizeLarge = __('Large', 'yet-another-stars-rating');\nvar yasrLeaveThisBlankText = __('Leave this blank if you don\\'t know what you\\'re doing.', 'yet-another-stars-rating');\nvar yasrOverallDescription = __('Remember: only the post author can rate here.', 'yet-another-stars-rating');\nvar yasrVisitorVotesDescription = __('This is the star set where your users will be able to vote', 'yet-another-stars-rating');\nvar YasrPrintSelectSize = function YasrPrintSelectSize(props) {\n  return /*#__PURE__*/React.createElement(\"form\", null, /*#__PURE__*/React.createElement(\"select\", {\n    value: props.size,\n    onChange: function onChange(e) {\n      return yasrSetStarsSize(props.setAttributes, e);\n    }\n  }, /*#__PURE__*/React.createElement(\"option\", {\n    value: \"--\"\n  }, yasrSelectSizeChoose, \"    \"), /*#__PURE__*/React.createElement(\"option\", {\n    value: \"small\"\n  }, yasrSelectSizeSmall, \"  \"), /*#__PURE__*/React.createElement(\"option\", {\n    value: \"medium\"\n  }, yasrSelectSizeMedium), /*#__PURE__*/React.createElement(\"option\", {\n    value: \"large\"\n  }, yasrSelectSizeLarge, \"  \")));\n};\n\nfunction yasrSetStarsSize(setAttributes, event) {\n  var selected = event.target.querySelector('option:checked');\n  setAttributes({\n    size: selected.value\n  });\n  event.preventDefault();\n}\n\nvar YasrPrintInputId = function YasrPrintInputId(props) {\n  var postId;\n\n  if (props.postId !== false) {\n    postId = props.postId;\n  }\n\n  return /*#__PURE__*/React.createElement(\"div\", null, /*#__PURE__*/React.createElement(\"input\", {\n    type: \"text\",\n    size: \"4\",\n    defaultValue: postId,\n    onKeyPress: function onKeyPress(e) {\n      return yasrSetPostId(props.setAttributes, e);\n    }\n  }));\n};\n\nfunction yasrSetPostId(setAttributes, event) {\n  if (event.key === 'Enter') {\n    var postIdValue = event.target.value; //postID is always a string, here I check if this string is made only by digits\n\n    var isNum = /^\\d+$/.test(postIdValue);\n\n    if (isNum === true || postIdValue === '') {\n      setAttributes({\n        postId: postIdValue\n      });\n    }\n\n    event.preventDefault();\n  }\n}\n\nvar YasrProText = function YasrProText() {\n  var YasrProText1 = __('To be able to customize this ranking, you need', 'yet-another-stars-rating');\n\n  var YasrProText2 = __('You can buy the plugin, including support, updates and upgrades, on', 'yet-another-stars-rating');\n\n  return /*#__PURE__*/React.createElement(\"h3\", null, YasrProText1, \"\\xA0\", /*#__PURE__*/React.createElement(\"a\", {\n    href: \"admin/js/src/guten/yasrGutenUtils?utm_source=wp-plugin&utm_medium=gutenberg_panel&utm_campaign=yasr_editor_screen&utm_content=rankings#yasr-pro\"\n  }, \"Yasr Pro.\"), /*#__PURE__*/React.createElement(\"br\", null), YasrProText2, \"\\xA0\", /*#__PURE__*/React.createElement(\"a\", {\n    href: \"admin/js/src/guten/yasrGutenUtils?utm_source=wp-plugin&utm_medium=gutenberg_panel&utm_campaign=yasr_editor_screen&utm_content=rankings\"\n  }, \"yetanotherstarsrating.com\"));\n};\nvar YasrNoSettingsPanel = function YasrNoSettingsPanel() {\n  return /*#__PURE__*/React.createElement(\"div\", null, /*#__PURE__*/React.createElement(YasrProText, null));\n};\n/**\n * This is the panel that for blocks that use size and postid attributes\n *\n * @param props\n * @return {JSX.Element}\n */\n\nvar YasrBlocksPanel = function YasrBlocksPanel(props) {\n  var name = props.block,\n      size = props.size,\n      setAttributes = props.setAttributes,\n      postId = props.postId;\n  var bottomDesc;\n\n  if (name === 'yet-another-stars-rating/visitor-votes') {\n    bottomDesc = yasrVisitorVotesDescription;\n  }\n\n  if (name === 'yet-another-stars-rating/overall-rating') {\n    bottomDesc = yasrOverallDescription;\n  }\n\n  return /*#__PURE__*/React.createElement(InspectorControls, null, name === 'yet-another-stars-rating/overall-rating' && /*#__PURE__*/React.createElement(YasrDivRatingOverall, null), /*#__PURE__*/React.createElement(PanelBody, {\n    title: \"Settings\"\n  }, /*#__PURE__*/React.createElement(\"h3\", null, yasrOptionalText), /*#__PURE__*/React.createElement(\"div\", {\n    className: \"yasr-guten-block-panel\"\n  }, /*#__PURE__*/React.createElement(\"label\", null, yasrLabelSelectSize), /*#__PURE__*/React.createElement(\"div\", null, /*#__PURE__*/React.createElement(YasrPrintSelectSize, {\n    size: size,\n    setAttributes: setAttributes\n  }))), /*#__PURE__*/React.createElement(\"div\", {\n    className: \"yasr-guten-block-panel\"\n  }, /*#__PURE__*/React.createElement(\"label\", null, \"Post ID\"), /*#__PURE__*/React.createElement(YasrPrintInputId, {\n    postId: postId,\n    setAttributes: setAttributes\n  }), /*#__PURE__*/React.createElement(\"div\", {\n    className: \"yasr-guten-block-explain\"\n  }, \"Use return (\\u21B5) to save.\"), /*#__PURE__*/React.createElement(\"p\", null, yasrLeaveThisBlankText)), /*#__PURE__*/React.createElement(\"div\", {\n    className: \"yasr-guten-block-panel\"\n  }, bottomDesc)));\n};\n/**\n * Return a div with the stars in order to vote for overall rating\n *\n * @param props\n * @returns {JSX.Element}\n */\n\nvar YasrDivRatingOverall = function YasrDivRatingOverall(props) {\n  if (JSON.parse(yasrConstantGutenberg.isFseElement) === true) {\n    return /*#__PURE__*/React.createElement(\"div\", {\n      className: \"yasr-guten-block-panel yasr-guten-block-panel-center\"\n    }, /*#__PURE__*/React.createElement(\"div\", null, __('This is a template file, you can\\'t rate here. You need to insert the rating inside the single post or page', 'yet-another-stars-rating')), /*#__PURE__*/React.createElement(\"br\", null));\n  } //Outside the editor page (e.g. widgets.php) wp.data.select('core/editor') is null\n  //So, in such cases, rating in overall widget must be disabled\n\n\n  if (wp.data.select('core/editor') === null) {\n    return /*#__PURE__*/React.createElement(React.Fragment, null);\n  }\n\n  var yasrOverallRateThis = __(\"Rate this article / item\", 'yet-another-stars-rating');\n\n  var overallRating = wp.data.select('core/editor').getCurrentPost().meta.yasr_overall_rating;\n\n  var rateCallback = function rateCallback(rating, done) {\n    rating = rating.toFixed(1);\n    rating = parseFloat(rating);\n    wp.data.dispatch('core/editor').editPost({\n      meta: {\n        yasr_overall_rating: rating\n      }\n    });\n    this.setRating(rating);\n    done();\n  };\n\n  return /*#__PURE__*/React.createElement(\"div\", {\n    className: \"yasr-guten-block-panel yasr-guten-block-panel-center\"\n  }, yasrOverallRateThis, /*#__PURE__*/React.createElement(\"div\", {\n    id: 'overall-rater',\n    ref: function ref() {\n      return yasrSetRaterValue(32, 'overall-rater', false, 0.1, false, overallRating, rateCallback);\n    }\n  }));\n};\n/**\n * Return null if size === large, or a string otherwise\n *\n * @param size\n * @returns {(null | string)}\n */\n\nvar YasrBlockSizeAttribute = function YasrBlockSizeAttribute(size) {\n  var sizeString = null;\n\n  if (size !== 'large') {\n    sizeString = \" size=\\\"\".concat(size, \"\\\"\");\n  }\n\n  return sizeString;\n};\n/**\n *\n * @param postId\n * @returns {(null | string)}\n */\n\nvar YasrBlockPostidAttribute = function YasrBlockPostidAttribute(postId) {\n  var isNum;\n  var postIdAttribute = null;\n  isNum = /^\\d+$/.test(postId);\n\n  if (isNum === true) {\n    postIdAttribute = ' postid=\"' + postId + '\"';\n  }\n\n  return postIdAttribute;\n};\n/**\n * Return an object with block attributes\n *\n * @param blockName\n * @returns {object}\n */\n\nvar YasrSetBlockAttributes = function YasrSetBlockAttributes(blockName) {\n  var blockAttributes = {\n    className: null,\n    //class name for the main div\n    shortCode: null //shortcode\n\n  };\n\n  if (blockName === 'yet-another-stars-rating/overall-rating') {\n    blockAttributes.className = 'yasr-overall-block';\n    blockAttributes.shortCode = 'yasr_overall_rating';\n  }\n\n  if (blockName === 'yet-another-stars-rating/visitor-votes') {\n    blockAttributes.className = 'yasr-vv-block';\n    blockAttributes.shortCode = 'yasr_visitor_votes';\n  }\n\n  return blockAttributes;\n};\n\n//# sourceURL=webpack://yet-another-stars-rating/./admin/js/src/guten/yasrGutenUtils.js?");

/***/ }),

/***/ "./includes/blocks/overall-rating/block.json":
/*!***************************************************!*\
  !*** ./includes/blocks/overall-rating/block.json ***!
  \***************************************************/
/***/ ((module) => {

eval("module.exports = JSON.parse('{\"title\":\"Yasr: Overall Rating\",\"$schema\":\"https://schemas.wp.org/trunk/block.json\",\"apiVersion\":2,\"name\":\"yet-another-stars-rating/overall-rating\",\"description\":\"Insert the author rating.\",\"icon\":\"star-half\",\"category\":\"yet-another-stars-rating\",\"keywords\":[\"rating\",\"author\",\"overall\"],\"attributes\":{\"size\":{\"type\":\"string\",\"default\":\"large\"},\"postId\":{\"type\":\"string\",\"default\":false}},\"supports\":{\"align\":[\"left\",\"center\",\"right\"]},\"editorScript\":\"yasr-shortcodes-blocks\"}');\n\n//# sourceURL=webpack://yet-another-stars-rating/./includes/blocks/overall-rating/block.json?");

/***/ }),

/***/ "./includes/blocks/ranking-overall-rating/block.json":
/*!***********************************************************!*\
  !*** ./includes/blocks/ranking-overall-rating/block.json ***!
  \***********************************************************/
/***/ ((module) => {

eval("module.exports = JSON.parse('{\"title\":\"Yasr: Ranking by overall rating\",\"$schema\":\"https://schemas.wp.org/trunk/block.json\",\"apiVersion\":2,\"name\":\"yet-another-stars-rating/overall-rating-ranking\",\"description\":\"This ranking shows the highest rated posts rated through the overall_rating shortcode.\",\"icon\":\"star-half\",\"category\":\"yet-another-stars-rating\",\"keywords\":[\"ranking\",\"highest\",\"chart\"],\"supports\":{\"align\":[\"left\",\"center\",\"right\"]},\"editorScript\":\"yasr-shortcodes-blocks\"}');\n\n//# sourceURL=webpack://yet-another-stars-rating/./includes/blocks/ranking-overall-rating/block.json?");

/***/ }),

/***/ "./includes/blocks/ranking-reviewers/block.json":
/*!******************************************************!*\
  !*** ./includes/blocks/ranking-reviewers/block.json ***!
  \******************************************************/
/***/ ((module) => {

eval("module.exports = JSON.parse('{\"title\":\"Yasr: Most Active Authors\",\"$schema\":\"https://schemas.wp.org/trunk/block.json\",\"apiVersion\":2,\"name\":\"yet-another-stars-rating/most-active-reviewers\",\"description\":\"This ranking shows the most active reviewers on your site.\",\"icon\":\"star-half\",\"category\":\"yet-another-stars-rating\",\"keywords\":[\"ranking\",\"highest\",\"most\",\"chart\",\"authors\"],\"supports\":{\"align\":[\"left\",\"center\",\"right\"]},\"editorScript\":\"yasr-shortcodes-blocks\"}');\n\n//# sourceURL=webpack://yet-another-stars-rating/./includes/blocks/ranking-reviewers/block.json?");

/***/ }),

/***/ "./includes/blocks/ranking-users/block.json":
/*!**************************************************!*\
  !*** ./includes/blocks/ranking-users/block.json ***!
  \**************************************************/
/***/ ((module) => {

eval("module.exports = JSON.parse('{\"title\":\"Yasr: Most Active Visitors\",\"$schema\":\"https://schemas.wp.org/trunk/block.json\",\"apiVersion\":2,\"name\":\"yet-another-stars-rating/most-active-users\",\"description\":\"This ranking shows the most active users, displaying the login name if logged in or \\\\\"Anonymous\\\\\" if not\",\"icon\":\"star-half\",\"category\":\"yet-another-stars-rating\",\"keywords\":[\"ranking\",\"highest\",\"most\",\"chart\",\"visitors\"],\"supports\":{\"align\":[\"left\",\"center\",\"right\"]},\"editorScript\":\"yasr-shortcodes-blocks\"}');\n\n//# sourceURL=webpack://yet-another-stars-rating/./includes/blocks/ranking-users/block.json?");

/***/ }),

/***/ "./includes/blocks/ranking-visitor-votes/block.json":
/*!**********************************************************!*\
  !*** ./includes/blocks/ranking-visitor-votes/block.json ***!
  \**********************************************************/
/***/ ((module) => {

eval("module.exports = JSON.parse('{\"title\":\"Yasr: Ranking by visitors votes\",\"$schema\":\"https://schemas.wp.org/trunk/block.json\",\"apiVersion\":2,\"name\":\"yet-another-stars-rating/visitor-votes-ranking\",\"description\":\"This ranking shows both the highest and most rated posts rated through the yasr_visitor_votes shortcode. For an item to appear in this chart, it has to be rated at least twice.\",\"icon\":\"star-half\",\"category\":\"yet-another-stars-rating\",\"keywords\":[\"ranking\",\"highest\",\"most\",\"chart\"],\"supports\":{\"align\":[\"left\",\"center\",\"right\"]},\"editorScript\":\"yasr-shortcodes-blocks\"}');\n\n//# sourceURL=webpack://yet-another-stars-rating/./includes/blocks/ranking-visitor-votes/block.json?");

/***/ }),

/***/ "./includes/blocks/user-rate-history/block.json":
/*!******************************************************!*\
  !*** ./includes/blocks/user-rate-history/block.json ***!
  \******************************************************/
/***/ ((module) => {

eval("module.exports = JSON.parse('{\"title\":\"Yasr: User Rate History\",\"$schema\":\"https://schemas.wp.org/trunk/block.json\",\"apiVersion\":2,\"name\":\"yet-another-stars-rating/user-rate-history\",\"description\":\"If user is logged in, this shortcode shows a list of all the ratings provided by the user on [yasr_visitor_votes] shortcode\",\"icon\":\"star-half\",\"category\":\"yet-another-stars-rating\",\"keywords\":[\"ratings\",\"list\",\"history\"],\"supports\":{\"align\":[\"left\",\"center\",\"right\"]},\"editorScript\":\"yasr-shortcodes-blocks\"}');\n\n//# sourceURL=webpack://yet-another-stars-rating/./includes/blocks/user-rate-history/block.json?");

/***/ }),

/***/ "./includes/blocks/visitor-votes/block.json":
/*!**************************************************!*\
  !*** ./includes/blocks/visitor-votes/block.json ***!
  \**************************************************/
/***/ ((module) => {

eval("module.exports = JSON.parse('{\"title\":\"Yasr: Visitor Votes\",\"$schema\":\"https://schemas.wp.org/trunk/block.json\",\"apiVersion\":2,\"name\":\"yet-another-stars-rating/visitor-votes\",\"description\":\"Insert the ability for your visitors to vote.\",\"icon\":\"star-half\",\"category\":\"yet-another-stars-rating\",\"keywords\":[\"rating\",\"visitor\",\"votes\",\"stars\"],\"attributes\":{\"size\":{\"type\":\"string\",\"default\":\"large\"},\"postId\":{\"type\":\"string\",\"default\":false}},\"supports\":{\"align\":[\"left\",\"center\",\"right\"]},\"editorScript\":\"yasr-shortcodes-blocks\"}');\n\n//# sourceURL=webpack://yet-another-stars-rating/./includes/blocks/visitor-votes/block.json?");

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module is referenced by other modules so it can't be inlined
/******/ 	__webpack_require__("./admin/js/src/guten/yasrGutenUtils.js");
/******/ 	__webpack_require__("./admin/js/src/guten/blocks/overallRating.js");
/******/ 	__webpack_require__("./admin/js/src/guten/blocks/visitorVotes.js");
/******/ 	__webpack_require__("./admin/js/src/guten/blocks/rankings.js");
/******/ 	__webpack_require__("./admin/js/src/guten/blocks/noStarsRankings.js");
/******/ 	var __webpack_exports__ = __webpack_require__("./admin/js/src/guten/blocks/userRateHistory.js");
/******/ 	
/******/ })()
;