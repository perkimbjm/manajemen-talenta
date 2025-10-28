export function html(strings: TemplateStringsArray, ...values: string[]) {
  let result = "";
  for (let i = 0; i < strings.length; i++) {
    result += strings[i];
    if (i < values.length) {
      result += values[i];
    }
  }
  return result;
}

export function createElement(htmlString: string) {
  const div = document.createElement('div');
  const removeScriptPattern = /<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi;
  div.innerHTML = htmlString.replace(removeScriptPattern, '').trim();
  if (div.firstElementChild instanceof HTMLElement) return div.firstElementChild

  return div
}