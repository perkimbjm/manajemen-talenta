import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";
import { iconsPlugin, getIconCollections } from "@egoist/tailwindcss-icons";

import wireui from "./vendor/wireui/wireui/tailwind.config";
import plugin from "tailwindcss/plugin";

export const daisyui = {
	themes: [
		{
			light: {
				...require("daisyui/src/theming/themes").light,
				primary: "#3F51B5",
				secondary: "#A1AEFB",
				accent: "#F88F2D",
				neutral: "#3d4451",
				"base-100": "#ffffff",
				"--rounded-box": "0.375rem",
				"--rounded-btn": "0.375rem",
			},
		},
		"night",
	],
	darkTheme: "night",
	base: false,
	styled: true,
	utils: true,
	prefix: "",
	logs: true,
	themeRoot: ":root",
};

const wireUIColors = {};

for (const [key, value] of Object.entries(wireui.theme.extend.colors)) {
	for (const [key2, value2] of Object.entries(value)) {
		wireUIColors[`${key}-${key2}`] = value2;
	}
}

/** @type {import('tailwindcss').Config} */
export default {
	darkMode: ["class", '[data-theme="dark"]'],
	content: [
		"./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
		"./storage/framework/views/*.php",
		"./resources/views/**/*.blade.php",
		"./resources/js/**/*.{ts,tsx,js,jsx,vue}",
		"./app/Livewire/**/*.php",
		"./vendor/wireui/wireui/src/*.php",
		"./vendor/wireui/wireui/ts/**/*.ts",
		"./vendor/wireui/wireui/src/WireUi/**/*.php",
		"./vendor/wireui/wireui/src/Components/**/*.php",
		"./vendor/power-components/livewire-powergrid/resources/views/**/*.php",
		"./vendor/power-components/livewire-powergrid/src/Themes/Tailwind.php",
	],

	safelist: [
		"sm:max-w-md",
		"md:max-w-xl",
		"lg:max-w-2xl",
		"lg:max-w-3xl",
		"xl:max-w-4xl",
		"xl:max-w-5xl",
		"2xl:max-w-6xl",
		"2xl:max-w-7xl",
	],

	theme: {
		extend: {
			colors: {
				...wireUIColors,
				"landing-bg": "#0984e3",
				"landing-primary": "#F4E63B",
				"landing-text": "#172850",
				"primary-light": "#FFBC52",
				"primary-dark": "#D4871B",
				"secondary-light": "#2D4C9C",
				"secondary-dark": "#294181",
			},
			fontFamily: {
				sans: ["Figtree", ...defaultTheme.fontFamily.sans],
				mono: ["Roboto Mono", ...defaultTheme.fontFamily.mono],
			},
			keyframes: {
				...wireui.theme.extend.keyframes,
			},
			animation: {
				...wireui.theme.extend.animation,
			},
		},
	},
	plugins: [
		forms,
		require("@tailwindcss/container-queries"),
		require("@tailwindcss/typography"),
		wireui.plugins,
		require("daisyui"),
		iconsPlugin({
			collections: getIconCollections(["mdi", "lucide"]),
		}),
		plugin(({ addVariant, addUtilities }) => {
			addVariant("global-peer-checked", ".peer:checked ~ * &");
			addVariant("popover-open", "&:popover-open");
			addVariant("sidebar-open", "html:has(.sidebar[open]) &");
			addVariant("sidebar-not-open", "html:has(.sidebar:not([open])) &");
			addVariant("sidebar-close", "html:has(.sidebar[close]) &");
			addVariant("sidebar-forced", "html:has(.sidebar[forced]) &");

			addUtilities({
				".scroll-animation": {
					animation: "var(--animation-name, none) linear forwards",
					"animation-timeline": "scroll()",
					"animation-range":
						"var(--animation-range-start, 0) var(--animation-range-end, 0)",
				},
				".scroll-animation-2": {
					"animation-name":
						"var(--animation-name-1, to-shadow), var(--animation-name-2)",
					"animation-timing-function": "linear",
					"animation-fill-mode": "forwards",
					"animation-timeline": "scroll()",
					"animation-range":
						"var(--animation-range-start, 0) var(--animation-range-end, 0)",
				},
				".bg-pattern-diagonal-v3": {
					"background-color": "#0984e3",
					opacity: "0.9",
					"background-size": "5px 5px",
					"background-image":
						"repeating-linear-gradient(45deg, #868cf7 0, #868cf7 0.5px, #0984e3 0, #0984e3 50%)",
				},
				".scrollbar-stable": {
					scrollbarGutter: "stable",
				},
			});
		}),
	],
};
