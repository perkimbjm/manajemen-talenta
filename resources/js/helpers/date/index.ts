import type { Alpine } from "alpinejs";
import type { FormatDistanceOptions, FormatOptions } from "date-fns";
import {
	differenceInMinutes,
	formatDistanceToNow,
	formatDistanceToNowStrict,
	setDefaultOptions,
} from "date-fns";
import { format } from "date-fns/format";
import { id } from "date-fns/locale/id";

setDefaultOptions({
	locale: id,
});

const DEFAULT_FORMAT_STR = "yyyy-MM-dd";

export function formatOrDistanceToNow(
	date: string | number | Date,
	options?: FormatDistanceOptions & {
		formatStr?: string;
		strict?: boolean;
	},
) {
	const difference = differenceInMinutes(new Date(), date);

	if (difference === 0) {
		return "baru saja";
	}

	if (difference >= 24 * 60) {
		return format(date, options?.formatStr || DEFAULT_FORMAT_STR); // Customize the format as needed
	}

	if (options?.strict) {
		return formatDistanceToNowStrict(date, options);
	}

	return formatDistanceToNow(date, { addSuffix: true, ...options });
}

export function getDate(date?: string | number | Date) {
	const value = date || new Date();
	return {
		format: (formatStr: string, options?: FormatOptions) =>
			format(value, formatStr, options),
		distanceToNow: (options?: FormatDistanceOptions) =>
			formatDistanceToNow(value, options),
		formatOrDistanceToNow: (options?: FormatDistanceOptions) =>
			formatOrDistanceToNow(value, options),
		toString() {
			return format(value, DEFAULT_FORMAT_STR);
		},
	};
}
