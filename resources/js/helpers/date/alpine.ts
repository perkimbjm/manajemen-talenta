import type { Alpine } from "alpinejs";
import { getDate } from "./index";

export default function AlpinePlugin(alpine: Alpine) {
	alpine.magic("date", () => {
		return getDate;
	});
}
