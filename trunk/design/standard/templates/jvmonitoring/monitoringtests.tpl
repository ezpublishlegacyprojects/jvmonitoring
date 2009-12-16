{foreach $monitor_result as $monitorID => $result}
	<h1 class="label">{$result.name}</h1>
	<p{if $result.is_up|not} class="erreur"{/if}>{if $result.is_up}OK{else}KO{/if}</p>
	{if $result.is_up|not}<p>{"Error details"} : {$result.error}</p>{/if}
{/foreach}

<p class="{if $has_errors}error{else}noerror{/if}">{$monitoring_status}</p>