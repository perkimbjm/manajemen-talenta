import type { Alpine, DirectiveData } from "alpinejs";

export function processTemplate(alpine: Alpine, templateRef: HTMLTemplateElement, parentElement?: HTMLElement, data?: Record<string, unknown>) {
  // Clone the template and get the root node - this is the node that we will
  // inject into the DOM.
  const content = templateRef.content.cloneNode(true) as HTMLElement;
  const clone = content.firstElementChild as HTMLElement | null;
  if (!clone) return
  // CAUTION: The following logic ASSUMES that the template-outlet directive has
  // an "x-data" scope binding on it. If it didn't we would have to change the
  // logic. But, I don't think Alpine.js has mechanics to solve this use-case
  // quite yet.
  if (data) {
    alpine.addScopeToNode(
      clone,
      // Use the "x-data" scope from the template-outlet element as a means to
      // supply initializing data to the clone (for constructor injection).
      data,
      // use the template-outlet element's parent to define the rest of the
      // scope chain.
      parentElement
    );
  }
  // Instead of leaving the template in the DOM, we're going to swap the
  // template with a comment hook. This isn't necessary; but, I think it leaves
  // the DOM more pleasant looking.
  const domHook = document.createComment(`Template outlet hook (${templateRef.localName}) with bindings`);
  //@ts-ignore
  domHook._template_outlet_ref = templateRef;
  //@ts-ignore
  domHook._template_outlet_clone = clone;
  // Swap the template-outlet element with the hook and clone.
  // --
  // NOTE: Doing this inside the mutateDom() method will pause Alpine's internal
  // MutationObserver, which allows us to perform DOM manipulation without
  // triggering actions in the framework. Then, we can call initTree() and
  // destroyTree() to have explicitly setup and teardowm DOM node bindings.

  return {
    domHook,
    clone,
  }
}

export function renderTemplate(alpine: Alpine, templateRef: HTMLTemplateElement, element: HTMLTemplateElement) {
  const result = processTemplate(alpine, templateRef, element.parentElement || undefined, alpine.closestDataStack(element).at(0));
  if (!result) return
  const { clone, domHook } = result

  alpine.mutateDom(
    function pauseMutationObserver() {
      element.after(domHook);
      domHook.after(clone);
      alpine.initTree(clone);
      element.remove();
      alpine.destroyTree(element);
    }
  );
}

function appendTemplate(alpine: Alpine, templateRef: HTMLTemplateElement, element: HTMLTemplateElement, data?: Record<string, unknown>) {
  const result = processTemplate(alpine, templateRef, element.parentElement || undefined, data);
  if (!result) return
  const { clone, domHook } = result

  alpine.mutateDom(
    function pauseMutationObserver() {
      element.append(clone);
      domHook.after(clone);
      alpine.initTree(clone);
    }
  );
}

export default function useTemplateOutlet(alpine: Alpine) {
  alpine.directive('template-outlet', (element, metadata, framework) => {
    // Get the template reference that we want to clone and render.
    const templateRef = framework.evaluate<HTMLTemplateElement>(metadata.expression);
    if (!templateRef) return

    renderTemplate(alpine, templateRef, element as HTMLTemplateElement)
  })

  alpine.magic('appendTemplate', () => {
    return (templateRef: HTMLTemplateElement, element: HTMLElement, data?: Record<string, unknown>) => {
      if (!templateRef) return
      appendTemplate(alpine, templateRef, element as HTMLTemplateElement, data)
    }
  })

  alpine.magic('processTemplate', () => {
    return (templateRef: HTMLTemplateElement, element: HTMLElement, data?: Record<string, unknown>) => {
      if (!templateRef) return undefined

      const result = processTemplate(alpine, templateRef, element as HTMLTemplateElement, data)
      return result?.clone
    }
  })
}