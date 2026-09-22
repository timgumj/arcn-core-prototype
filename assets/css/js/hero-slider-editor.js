(function (wp) {
	"use strict";

	if (
		!wp ||
		!wp.blocks ||
		!wp.element ||
		!wp.components
	) {
		return;
	}

	const el = wp.element.createElement;
	const TextControl = wp.components.TextControl;
	const Button = wp.components.Button;
	const MediaUpload = wp.blockEditor.MediaUpload;
	const MediaUploadCheck = wp.blockEditor.MediaUploadCheck;


	function registerArcnHeroSlider() {

		/*
		 * Avoid registering twice if WordPress
		 * has already loaded the block.
		 */
		if (
			wp.blocks.getBlockType(
				"arcn/hero-slider"
			)
		) {
			return;
		}


		wp.blocks.registerBlockType(
			"arcn/hero-slider",
			{
				apiVersion: 3,

				title: "ARCN Hero Slider",

				description:
					"Homepage image slider.",

				icon: "images-alt2",

				category: "design",

				attributes: {

					image1: {
						type: "string",
						default: ""
					},

					source1: {
						type: "string",
						default: ""
					},

					image2: {
						type: "string",
						default: ""
					},

					source2: {
						type: "string",
						default: ""
					},

					image3: {
						type: "string",
						default: ""
					},

					source3: {
						type: "string",
						default: ""
					},

					image4: {
						type: "string",
						default: ""
					},

					source4: {
						type: "string",
						default: ""
					}
				},

				supports: {
					html: false,
					align: false,
					customClassName: false
				},


				edit: function (props) {
					const blockProps = wp.blockEditor.useBlockProps({
						className: "arcn-hero-slider-editor"
					});

					const attributes =
						props.attributes;

					const setAttributes =
						props.setAttributes;


					const fields = [];


					for (
						let number = 1;
						number <= 4;
						number++
					) {

						const imageKey =
							"image" + number;

						const sourceKey =
							"source" + number;


						fields.push(
							el(
								"div",
								{
									key:
										"slide-" +
										number,

									className:
										"arcn-hero-slider-editor__slide"
								},

								el(
									"h4",
									{
										className:
											"arcn-hero-slider-editor__slide-title"
									},
									"Slide " +
										number
								),

								attributes[imageKey] && el("img", {
									src: attributes[imageKey],
									alt: "Slide " + number + " preview",
									style: { display: "block", maxWidth: "100%", maxHeight: "200px", marginBottom: "12px" }
								}),
								el(MediaUploadCheck, null, el(MediaUpload, {
									allowedTypes: ["image"],
									onSelect: function (media) {
										if (media && media.url) setAttributes({ [imageKey]: media.url });
									},
									render: function (control) {
										return el(Button, { variant: "secondary", onClick: control.open },
											attributes[imageKey] ? "Replace image" : "Choose image");
									}
								})),
								el(
									TextControl,
									{
										label:
											"Image URL",

										value:
											attributes[
												imageKey
											] || "",

										placeholder:
											"https://...",

										onChange:
											function (
												value
											) {

												const update =
													{};

												update[
													imageKey
												] =
													value;

												setAttributes(
													update
												);
											}
									}
								),

								el(
									TextControl,
									{
										label:
											"Image source",

										value:
											attributes[
												sourceKey
											] || "",

										placeholder:
											"Photo by...",

										onChange:
											function (
												value
											) {

												const update =
													{};

												update[
													sourceKey
												] =
													value;

												setAttributes(
													update
												);
											}
									}
								)
							)
						);
					}


					return el(
						"div",
						blockProps,

						el(
							"div",
							{
								className:
									"arcn-hero-slider-editor__header"
							},

							el(
								"h3",
								{
									className:
										"arcn-hero-slider-editor__title"
								},
								"ARCN Hero Slider"
							),

							el(
								"p",
								{
									className:
										"arcn-hero-slider-editor__description"
								},
								"Choose an image or paste its URL, then edit the source text for each slide. Save the page to update the slideshow."
							)
						),

						fields
					);
				},


				save: function () {

					/*
					 * Dynamic block.
					 * Frontend output comes from PHP.
					 */
					return null;
				}
			}
		);
	}


	/*
	 * Wait until WordPress has finished setting up
	 * the editor before registering the block.
	 */
	if (
		wp.domReady
	) {

		wp.domReady(
			registerArcnHeroSlider
		);

	} else {

		registerArcnHeroSlider();
	}

}(window.wp));
