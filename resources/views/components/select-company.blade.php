<div>
    <select id="branches" name="branches" class="mt-1 block w-full py-2 pl-3 pr-8 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500 sm:text-sm
        @if(is_null($branchSelected)) blinking-border @endif">
        <option disabled value="" @selected(is_null($branchSelected))>Selecione uma filial</option>
        @foreach ($branches as $company)
            <optgroup label="{{$company['codemp']}} - {{ $company['nomemp'] }}">
                @foreach ($company['filiais'] as $branch)
                    @php
                        $value = $company['codemp'].'.'.$branch['codfil'];
                    @endphp
                    <option value="{{$value}}" @selected($branchSelected == $value)>
                        {{ $value }} - {{ $branch['nomfil'] }}
                    </option>
                @endforeach
            </optgroup>
        @endforeach
    </select>
</div>

<style>
    /* CSS para o efeito de piscar nas bordas */
    .blinking-border {
        animation: blink-border 1s steps(5, start) infinite;
    }
    @keyframes blink-border {
        50% {
            border-color: rgb(241, 128, 30)
        }
        100% {
            border-color: rgb(156, 60, 4);
        }
    }
</style>

<script>
    document.getElementById('branches').addEventListener('change', function () {
        fetch('/mark/change-branch', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({branch: this.value})
        })
            .then(data => {
                showToast('Filial trocada com sucesso!', 'success');
                this.classList.remove('blinking-border');
                checkSelections();
            })
            .catch(error => {
                showToast('Falha ao trocar filial!', 'warning');
            });
    });

    document.getElementById('branches').addEventListener('focus', function () {
        this.classList.remove('blinking-border');
    });
</script>
