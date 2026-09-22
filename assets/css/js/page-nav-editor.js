(function (wp) {
	"use strict";

	const el = wp.element.createElement;
	const __ = wp.i18n.__;
	const excludedClass = "arcn-nav-excluded";
	function classes(value) {
		return (value || "").split(/\s+/).filter(Boolean);
	}

	const withNavigationSettings = wp.compose.createHigherOrderComponent(function (BlockEdit) {
		return function NavigationSettings(props) {
			const section = wp.data.useSelect(function (select) {
				const editor = select("core/block-editor");
				const block = editor.getBlock(props.clientId);
				if (!block) return null;
				if (block.name === "core/group" && classes(block.attributes.className).includes("arcn-content-section")) {
					return block;
				}
				if (block.name !== "core/heading" || !classes(block.attributes.className).includes("arcn-section-heading")) {
					return null;
				}
				return editor.getBlockParents(props.clientId).slice().reverse().map(function (id) {
					return editor.getBlock(id);
				}).find(function (parent) {
					return parent && parent.name === "core/group" && classes(parent.attributes.className).includes("arcn-content-section");
				}) || null;
			}, [props.clientId]);

			return el(wp.element.Fragment, null,
				el(BlockEdit, props),
				props.isSelected && section && el(wp.blockEditor.InspectorControls, null,
					el(wp.components.PanelBody, { title: __("Page navigation", "arcn-core-prototype") },
						el(wp.components.ToggleControl, {
							label: __("Show in left navigation", "arcn-core-prototype"),
							checked: !classes(section.attributes.className).includes(excludedClass),
							help: __("Turn off to keep this heading and content under the previous navigation item. If there is no previous item, this section has no navigation entry.", "arcn-core-prototype"),
							onChange: function (show) {
								const nextClasses = classes(section.attributes.className).filter(function (name) {
									return name !== excludedClass;
								});
								if (!show) nextClasses.push(excludedClass);
								wp.data.dispatch("core/block-editor").updateBlockAttributes(section.clientId, {
									className: nextClasses.join(" ")
								});
							}
						})
					)
				)
			);
		};
	}, "withArcnNavigationSettings");

	wp.hooks.addFilter("editor.BlockEdit", "arcn/page-navigation-settings", withNavigationSettings);
})(window.wp);
