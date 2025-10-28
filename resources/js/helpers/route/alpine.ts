import type { Alpine } from "alpinejs";
import { route } from "./index";

export default function (alpine: Alpine) {
	alpine.magic("route", () => {
		return route;
	});
}
