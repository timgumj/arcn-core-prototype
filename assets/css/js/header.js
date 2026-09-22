(function () {
	"use strict";

	const MOBILE_MAX_WIDTH = 980;

	const HEADER_SELECTOR =
		".arcn-site-header";

	const INNER_SELECTOR =
		".arcn-site-header__inner";

	const NAV_SELECTOR =
		".arcn-primary-nav";

	const ACTIVE_CLASS =
		"arcn-menu-active";

	const OPEN_CLASS =
		"arcn-submenu-open";

	let resizeFrame = 0;


	/* =====================================================
	   VIEWPORT
	   ===================================================== */

	function isMobileViewport() {
		return (
			window.innerWidth <=
			MOBILE_MAX_WIDTH
		);
	}


	/* =====================================================
	   ELEMENT HELPERS
	   ===================================================== */

	function getHeader() {
		return document.querySelector(
			HEADER_SELECTOR
		);
	}


	function getHeaderInner() {
		return document.querySelector(
			INNER_SELECTOR
		);
	}


	function getNavigation() {
		return document.querySelector(
			NAV_SELECTOR
		);
	}


	function getOriginalMenu() {

		const nav =
			getNavigation();


		if (!nav) {
			return null;
		}


		return nav.querySelector(
			"ul.wp-block-navigation__container"
		);
	}


	function getDirectSubmenu(item) {

		if (
			!(item instanceof Element)
		) {
			return null;
		}


		for (
			const child of item.children
		) {

			if (
				child.matches(
					"ul.wp-block-navigation__submenu-container"
				)
			) {
				return child;
			}
		}


		return null;
	}


	function getDirectLink(item) {

		if (
			!(item instanceof Element)
		) {
			return null;
		}


		for (
			const child of item.children
		) {

			if (
				child.matches(
					"a.wp-block-navigation-item__content"
				)
			) {
				return child;
			}
		}


		return null;
	}


	function getItemLabel(item) {

		const link =
			getDirectLink(item);


		if (!link) {
			return "";
		}


		const label =
			link.querySelector(
				".wp-block-navigation-item__label"
			);


		return String(
			label
				? label.textContent
				: link.textContent
		)
			.trim()
			.replace(/\s+/g, " ");
	}


	/* =====================================================
	   HEADER OFFSET
	   ===================================================== */

	function updateHeaderOffset() {

		if (!isMobileViewport()) {

			document.documentElement
				.style
				.removeProperty(
					"--arcn-mobile-header-bottom"
				);

			return;
		}


		const header =
			getHeader();


		if (!header) {
			return;
		}


		const rect =
			header.getBoundingClientRect();


		const bottom =
			Math.max(
				0,
				Math.round(
					rect.bottom
				)
			);


		document.documentElement
			.style
			.setProperty(
				"--arcn-mobile-header-bottom",
				bottom + "px"
			);
	}


	function scheduleHeaderOffset() {

		if (resizeFrame) {
			return;
		}


		resizeFrame =
			window.requestAnimationFrame(
				function () {

					resizeFrame = 0;

					updateHeaderOffset();
				}
			);
	}


	/* =====================================================
	   CLEAN CLONED MENU
	   ===================================================== */

	function cleanClonedMenu(menu) {

		/*
		 * WordPress also puts the navigation block's custom class on
		 * its root list. Do not copy the source navigation's mobile
		 * hiding styles onto our visible menu.
		 */
		menu.classList.remove("arcn-primary-nav");

		/*
		 * Remove WordPress Core submenu buttons.
		 * Our own + / - buttons replace them.
		 */
		menu.querySelectorAll(
			".wp-block-navigation__submenu-icon, " +
			".wp-block-navigation-submenu__toggle"
		).forEach(
			function (button) {

				button.remove();
			}
		);


		menu.removeAttribute(
			"style"
		);


		menu.classList.add(
			"arcn-mobile-menu"
		);


		menu.querySelectorAll(
			"ul.wp-block-navigation__submenu-container"
		).forEach(
			function (submenu) {

				submenu.removeAttribute(
					"style"
				);


				submenu.classList.add(
					"arcn-mobile-submenu"
				);
			}
		);
	}


	/* =====================================================
	   FIND CUSTOM SUBMENU BUTTON
	   ===================================================== */

	function getCustomSubmenuButton(
		item
	) {

		for (
			const child of item.children
		) {

			if (
				child.classList &&
				child.classList.contains(
					"arcn-mobile-submenu-toggle"
				)
			) {
				return child;
			}
		}


		return null;
	}


	/* =====================================================
	   SUBMENU OPEN / CLOSE
	   ===================================================== */

	function setSubmenuState(
		item,
		open
	) {

		const button =
			getCustomSubmenuButton(
				item
			);


		if (open) {

			item.classList.add(
				OPEN_CLASS
			);

		} else {

			item.classList.remove(
				OPEN_CLASS
			);
		}


		if (
			button instanceof
			HTMLButtonElement
		) {

			const label =
				button.dataset.label ||
				getItemLabel(
					item
				);


			button.textContent =
				open
					? "−"
					: "+";


			button.setAttribute(
				"aria-expanded",
				open
					? "true"
					: "false"
			);


			button.setAttribute(
				"aria-label",
				(
					open
						? "Close submenu for "
						: "Open submenu for "
				) +
				label
			);
		}


		/*
		 * Closing a parent also closes
		 * every nested submenu.
		 */
		if (!open) {

			item.querySelectorAll(
				"." + OPEN_CLASS
			).forEach(
				function (nestedItem) {

					nestedItem
						.classList
						.remove(
							OPEN_CLASS
						);


					const nestedButton =
						getCustomSubmenuButton(
							nestedItem
						);


					if (
						nestedButton instanceof
						HTMLButtonElement
					) {

						const nestedLabel =
							nestedButton
								.dataset
								.label ||
							getItemLabel(
								nestedItem
							);


						nestedButton
							.textContent =
							"+";


						nestedButton
							.setAttribute(
								"aria-expanded",
								"false"
							);


						nestedButton
							.setAttribute(
								"aria-label",
								"Open submenu for " +
								nestedLabel
							);
					}
				}
			);
		}
	}


	/* =====================================================
	   CLOSE TOP LEVEL SIBLINGS
	   ===================================================== */

	function closeTopLevelSiblings(
		currentItem,
		menu
	) {

		for (
			const item of menu.children
		) {

			if (
				item ===
				currentItem
			) {
				continue;
			}


			if (
				item.classList
					.contains(
						"arcn-has-submenu"
					)
			) {

				setSubmenuState(
					item,
					false
				);
			}
		}
	}


	/* =====================================================
	   ADD + / - BUTTONS
	   ===================================================== */

	function prepareMenuItems(
		menu
	) {

		let submenuIndex = 0;


		menu.querySelectorAll(
			"li.wp-block-navigation-item"
		).forEach(
			function (item) {

				const submenu =
					getDirectSubmenu(
						item
					);


				if (!submenu) {
					return;
				}


				submenuIndex += 1;


				item.classList.add(
					"arcn-has-submenu"
				);


				submenu.id =
					"arcn-mobile-submenu-" +
					submenuIndex;


				const label =
					getItemLabel(
						item
					);


				const button =
					document.createElement(
						"button"
					);


				button.type =
					"button";


				button.className =
					"arcn-mobile-submenu-toggle";


				button.textContent =
					"+";


				button.dataset.label =
					label;


				button.setAttribute(
					"aria-expanded",
					"false"
				);


				button.setAttribute(
					"aria-controls",
					submenu.id
				);


				button.setAttribute(
					"aria-label",
					"Open submenu for " +
					label
				);


				button.addEventListener(
					"click",
					function (event) {

						event.preventDefault();
						event.stopPropagation();


						const isOpen =
							item
								.classList
								.contains(
									OPEN_CLASS
								);


						if (
							item.parentElement ===
							menu
						) {

							closeTopLevelSiblings(
								item,
								menu
							);
						}


						setSubmenuState(
							item,
							!isOpen
						);
					}
				);


				item.insertBefore(
					button,
					submenu
				);
			}
		);
	}


	/* =====================================================
	   OPEN FIRST PARENT
	   ===================================================== */

	function openFirstParent(
		menu
	) {

		for (
			const item of menu.children
		) {

			if (
				item.classList
					.contains(
						"arcn-has-submenu"
					)
			) {

				setSubmenuState(
					item,
					true
				);

				break;
			}
		}
	}


	/* =====================================================
	   FIND MENU LINK
	   ===================================================== */

	function normalizeText(
		value
	) {

		return String(
			value || ""
		)
			.trim()
			.toLowerCase()
			.replace(/\s+/g, " ");
	}


	function findLinkByLabels(
		menu,
		labels
	) {

		const normalized =
			labels.map(
				normalizeText
			);


		const links =
			menu.querySelectorAll(
				"a.wp-block-navigation-item__content"
			);


		for (
			const link of links
		) {

			const labelElement =
				link.querySelector(
					".wp-block-navigation-item__label"
				);


			const label =
				normalizeText(
					labelElement
						? labelElement
							.textContent
						: link.textContent
				);


			if (
				normalized.includes(
					label
				)
			) {
				return link;
			}
		}


		return null;
	}


	/* =====================================================
	   ACTION LINKS
	   ===================================================== */

	function createActionLink(
		label,
		url
	) {

		const link =
			document.createElement(
				"a"
			);


		link.className =
			"arcn-mobile-action";


		link.textContent =
			label;


		link.href =
			url;


		return link;
	}


	function addMobileActions(
		panel,
		menu
	) {

		const wrapper =
			document.createElement(
				"div"
			);


		wrapper.className =
			"arcn-mobile-actions";


		const events =
			findLinkByLabels(
				menu,
				[
					"Events"
				]
			);


		const contact =
			findLinkByLabels(
				menu,
				[
					"Contact",
					"Contact Us"
				]
			);


		wrapper.appendChild(
			createActionLink(
				"Events",
				events
					? events.href
					: "/event/"
			)
		);


		wrapper.appendChild(
			createActionLink(
				"Contact",
				contact
					? contact.href
					: "/contact-us/"
			)
		);


		panel.appendChild(
			wrapper
		);
	}


	/* =====================================================
	   CREATE MOBILE MENU
	   ===================================================== */

	function buildMobileMenu() {

		const header =
			getHeader();


		const headerInner =
			getHeaderInner();


		const originalMenu =
			getOriginalMenu();


		if (
			!header ||
			!headerInner ||
			!originalMenu
		) {
			return false;
		}


		/*
		 * Do not build twice.
		 */
		if (
			header.querySelector(
				".arcn-mobile-menu-toggle"
			)
		) {
			return true;
		}


		/* -------------------------------------------------
		   TOGGLE
		   ------------------------------------------------- */

		const toggle =
			document.createElement(
				"button"
			);


		toggle.type =
			"button";


		toggle.className =
			"arcn-mobile-menu-toggle";


		toggle.setAttribute(
			"aria-expanded",
			"false"
		);


		toggle.setAttribute(
			"aria-label",
			"Open menu"
		);


		toggle.innerHTML =
			'<span class="' +
			'arcn-mobile-menu-toggle__line ' +
			'arcn-mobile-menu-toggle__line--one' +
			'"></span>' +
			'<span class="' +
			'arcn-mobile-menu-toggle__line ' +
			'arcn-mobile-menu-toggle__line--two' +
			'"></span>';


		/* -------------------------------------------------
		   PANEL
		   ------------------------------------------------- */

		const panel =
			document.createElement(
				"div"
			);


		panel.className =
			"arcn-mobile-menu-panel";


		panel.setAttribute(
			"aria-hidden",
			"true"
		);


		const menu =
			originalMenu.cloneNode(
				true
			);


		cleanClonedMenu(
			menu
		);


		prepareMenuItems(
			menu
		);


		panel.appendChild(
			menu
		);


		addMobileActions(
			panel,
			menu
		);


		/*
		 * Add custom button to header.
		 */
		headerInner.appendChild(
			toggle
		);


		/*
		 * Add mobile panel below header.
		 */
		header.appendChild(
			panel
		);


		/* -------------------------------------------------
		   OPEN
		   ------------------------------------------------- */

		function openMenu() {

			if (
				!isMobileViewport()
			) {
				return;
			}


			updateHeaderOffset();


			panel.classList.add(
				"is-open"
			);


			toggle.classList.add(
				"is-open"
			);


			document.documentElement
				.classList
				.add(
					ACTIVE_CLASS
				);


			if (document.body) {

				document.body
					.classList
					.add(
						ACTIVE_CLASS
					);
			}


			toggle.setAttribute(
				"aria-expanded",
				"true"
			);


			toggle.setAttribute(
				"aria-label",
				"Close menu"
			);


			panel.setAttribute(
				"aria-hidden",
				"false"
			);


			/*
			 * Reset submenu state.
			 */
			menu.querySelectorAll(
				"." + OPEN_CLASS
			).forEach(
				function (item) {

					setSubmenuState(
						item,
						false
					);
				}
			);


			/*
			 * Match old ARCN behaviour:
			 * first parent open.
			 */
			openFirstParent(
				menu
			);


			panel.scrollTop =
				0;
		}


		/* -------------------------------------------------
		   CLOSE
		   ------------------------------------------------- */

		function closeMenu() {

			panel.classList.remove(
				"is-open"
			);


			toggle.classList.remove(
				"is-open"
			);


			document.documentElement
				.classList
				.remove(
					ACTIVE_CLASS
				);


			if (document.body) {

				document.body
					.classList
					.remove(
						ACTIVE_CLASS
					);
			}


			toggle.setAttribute(
				"aria-expanded",
				"false"
			);


			toggle.setAttribute(
				"aria-label",
				"Open menu"
			);


			panel.setAttribute(
				"aria-hidden",
				"true"
			);
		}


		/* -------------------------------------------------
		   TOGGLE CLICK
		   ------------------------------------------------- */

		toggle.addEventListener(
			"click",
			function () {

				if (
					panel.classList
						.contains(
							"is-open"
						)
				) {

					closeMenu();

				} else {

					openMenu();
				}
			}
		);


		/* -------------------------------------------------
		   LINKS CLOSE MENU
		   ------------------------------------------------- */

		panel.querySelectorAll(
			"a"
		).forEach(
			function (link) {

				link.addEventListener(
					"click",
					function () {

						closeMenu();
					}
				);
			}
		);


		/* -------------------------------------------------
		   ESCAPE
		   ------------------------------------------------- */

		document.addEventListener(
			"keydown",
			function (event) {

				if (
					event.key ===
						"Escape" &&
					panel.classList
						.contains(
							"is-open"
						)
				) {

					closeMenu();

					toggle.focus();
				}
			}
		);


		/* -------------------------------------------------
		   DESKTOP RESIZE
		   ------------------------------------------------- */

		window.addEventListener(
			"resize",
			function () {

				if (
					!isMobileViewport()
				) {

					closeMenu();
				}
			},
			{
				passive: true
			}
		);


		return true;
	}


	/* =====================================================
	   RETRY IF WORDPRESS NAV IS LATE
	   ===================================================== */

	function initialiseMobileMenu() {

		if (
			buildMobileMenu()
		) {
			return;
		}


		const observer =
			new MutationObserver(
				function () {

					if (
						buildMobileMenu()
					) {

						observer.disconnect();
					}
				}
			);


		observer.observe(
			document.documentElement,
			{
				childList:
					true,

				subtree:
					true
			}
		);


		/*
		 * Do not observe forever.
		 */
		window.setTimeout(
			function () {

				observer.disconnect();
			},
			5000
		);
	}


	/* =====================================================
	   RESPONSIVE POSITION
	   ===================================================== */

	function handleViewportChange() {

		scheduleHeaderOffset();
	}


	/* =====================================================
	   START
	   ===================================================== */

	function start() {

		initialiseMobileMenu();

		updateHeaderOffset();


		window.addEventListener(
			"resize",
			handleViewportChange,
			{
				passive: true
			}
		);


		window.addEventListener(
			"orientationchange",
			function () {

				window.setTimeout(
					updateHeaderOffset,
					150
				);
			},
			{
				passive: true
			}
		);


		if (
			window.visualViewport
		) {

			window.visualViewport
				.addEventListener(
					"resize",
					scheduleHeaderOffset,
					{
						passive: true
					}
				);
		}
	}


	if (
		document.readyState ===
		"loading"
	) {

		document.addEventListener(
			"DOMContentLoaded",
			start,
			{
				once: true
			}
		);

	} else {

		start();
	}
})();
