const fs = require("node:fs");
const path = require("node:path");

export function getLocalCollection(dir, name) {
	const files = fs.readdirSync(dir);
	const collection = {
		[name]: {
			icons: {},
		},
	};

	let stat;
	for (const file of files) {
		const filePath = `${dir}/${file}`;
		try {
			stat = fs.lstatSync(filePath);
		} catch (err) {
			continue;
		}
		if (stat.isFile()) {
			const svg = fs.readFileSync(filePath, "utf-8");
			const filename = path.basename(file, ".svg");
			collection[name].icons[filename] = {
				body: svg.replace(/<svg[^>]*>/, "").replace(/<\/svg>/, ""),
				width: 24,
				height: 24,
			};
		}
	}
	return collection;
}
