<div id="app">
    <h2>Лиды и доступные переводчики</h2>

    <div v-for="item in leads" :key="item.lead.id" class="lead-card">
        <h3>{{ item.lead.title }}</h3>
        <p>Deadline: {{ item.lead.deadline }}</p>
        <p>Status: {{ item.lead.status }}</p>

        <ul>
            <li v-for="translator in item.translators" :key="translator.id" class="translator-card">
                {{ translator.name }} — {{ translator.availability }}
                <button @click="setTranslatorToLead(item.lead.id, translator.id)">
                    Назначить переводчика
                </button>
            </li>
        </ul>
    </div>
</div>


<script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
<script>
const { createApp, ref, onMounted } = Vue;

createApp({
    setup() {
        const leads = ref([]);

        const getCorrectTranslatorsByLead = () => {
            fetch('/translatorsDistribution/lead/get-correct-translators-by-lead', {
                method: 'GET',
            })
            .then(response => response.json())
            .then(dataFromServer => {
                leads.value = dataFromServer;
            })
            .catch(error => console.error(error));
        };

        const setTranslatorToLead = (leadId, translatorId) => {
            fetch('/translatorsDistribution/lead/set-translator-to-lead', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ leadId, translatorId })
            })
            .then(response => response.json())
            .then(dataFromServer => {
                leads.value = dataFromServer;
            })
            .catch(error => console.error(error));
        };
        
        onMounted(() => {
            getCorrectTranslatorsByLead();
        });

        return {
           leads,
           setTranslatorToLead
        };
    }
}).mount('#app');
</script>


<style>
.lead-list {
  list-style: none;
  padding: 0;
}

.lead-card {
  border: 1px solid #ccc;
  border-radius: 6px;
  margin-bottom: 16px;
  padding: 12px;
  background: #f9f9f9;
}

.translator-list {
  list-style: none;
  padding-left: 16px;
}

.translator-card {
  margin-bottom: 6px;
  padding: 6px;
  border-left: 3px solid #4caf50;
  background: #fff;
}
</style>