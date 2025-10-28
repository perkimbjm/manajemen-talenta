import { RouteList } from "../../generated/route-list";

type TRouteList = typeof RouteList;

type RouteNameWithoutParameters = {
	[K in keyof TRouteList]: TRouteList[K]["parameters"] extends null ? K : never;
}[keyof TRouteList];

type RouteNameWithParameters = {
	[K in keyof TRouteList]: TRouteList[K]["parameters"] extends null ? never : K;
}[keyof TRouteList];

export function route<
	RouteName extends RouteNameWithParameters,
	Parameters extends TRouteList[RouteName]["parameters"] &
		Record<string, string>,
>(name: RouteName, paramaters: Parameters): string;

export function route<RouteName extends RouteNameWithoutParameters>(
	name: RouteName,
	paramaters?: Record<string, string>,
): string;

// export function route<RouteName extends keyof TRouteList, Parameters = TRouteList[RouteName]['parameters'] & null>(name: RouteName, paramaters?: Record<string, string>): string

export function route(
	name: string,
	paramaters?: Record<string, string> | null,
) {
	const Route = RouteList[name];
	return parseRoutePath(Route.path, Route.parameters, paramaters);
}

export function parseRoutePath(
	path: string,
	parameters: Record<string, string> | null,
	data?: Record<string, string> | null,
) {
	const queryStringMap = new Map(Object.entries(data || {}));
	let parsedPath = path;

	if (parameters) {
		Object.entries(parameters).map(([key, value]) => {
			const combined = key !== value ? `${key}:${value}` : key;

			const paramValue = data?.[key];
			if (!paramValue && typeof paramValue !== "number") {
				throw new Error(`Missing parameter: ${key}`);
			}

			queryStringMap.delete(key);

			parsedPath = parsedPath.replace(`{${combined}}`, paramValue.toString());
		});
	}

	const query = new URLSearchParams(
		Object.fromEntries(queryStringMap.entries()),
	);

	if (query.size !== 0) {
		parsedPath = `${parsedPath}?${query.toString()}`;
	}

	return parsedPath;
}
