@props(['ok' => session('ok'), 'error' => session('error')])
<div
  x-data="{ show: {{ $ok || $error ? 'true':'false' }}, msg: @js($ok ?: $error), kind: @js($ok ? 'ok' : ($error ? 'error' : '')) }"
  x-show="show" x-transition
  x-init="if(show){ setTimeout(()=>show=false, 3000) }"
  class="fixed bottom-4 left-1/2 -translate-x-1/2 z-50">
  <div class="px-4 py-2 rounded-xl text-sm shadow-lg"
       :class="kind==='ok' ? 'bg-emerald-600 text-white' : 'bg-rose-600 text-white'">
    <span x-text="msg"></span>
  </div>
</div>
