import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import path from "node:path";

export default defineConfig({
	plugins: [
		laravel({
			input: ["resources/css/app.css", "resources/js/app.js"],
			refresh: true,
		}),
	],
	resolve: {
		alias: {
			_: path.resolve(__dirname, "resources"),
			"@": path.resolve(__dirname, "resources/js"),
			"~": path.resolve(__dirname, "resources/css"),
			$: path.resolve(__dirname, "vendor"),
		},
	},
	server: {
		watch: {
			ignored: [
				"**/.devenv/**",
				"**/.direnv/**",
				"**/flake.nix",
				"**/flake.lock",
				"**/.envrc",
			],
		},
	},
});
