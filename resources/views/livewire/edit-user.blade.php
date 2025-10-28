<div class="text-base-content">
 Hello world
 Lorem ipsum dolor sit amet, consectetur adipisicing elit. Delectus cumque optio ad omnis quaerat necessitatibus minima?
 Ullam, nihil, tempora id unde exercitationem laborum earum enim reiciendis odit quidem nulla vel.

 <p class="">
  Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptatibus saepe enim tenetur, dolores quia deleniti modi
  accusamus ducimus placeat, deserunt possimus dolor pariatur temporibus assumenda doloremque similique. Quam, dolores
  natus.
 </p>

 <p class="">
  Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptatibus saepe enim tenetur, dolores quia deleniti modi
  accusamus ducimus placeat, deserunt possimus dolor pariatur temporibus assumenda doloremque similique. Quam, dolores
  natus.
 </p>

 <button
  type="button"
  wire:click='incrementCounter'
  class="btn"
 >Increment Counter</button>
 <button
  x-on:click="document.getElementById('NewDialog').showModal()"
  class="btn btn-primary"
 >New Modal</button>

 <button wire:click="$dispatch('openModal', { component: 'new-user', modalAttributes: {destroyOnClose: true} })">
  New User
 </button>
</div>
