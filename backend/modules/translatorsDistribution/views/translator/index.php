<div id="app">
    <h2>Все переводчики</h2>
    <div class="cards-container">
        <div
        v-for="translator in translators"
        :key="translator.id"
        class="translator-card"
        >
        <div class="card-header">
            <h3>{{ translator.name }}</h3>
        </div>
        <div class="card-body">
            <p><strong>Availability:</strong> {{ translator.availability }}</p>
            <p><strong>ID:</strong> {{ translator.id }}</p>
        </div>
            <div class="card-footer">
                <select v-model="translator.newAvailability">
                    <option value="weekday">Weekday</option>
                    <option value="flexible">Flexible</option>
                </select>
                <button @click="setTranslatorAvailability(translator.id, translator.newAvailability)">
                    Сменить режим работы
                </button>
            </div>
        </div>
    </div>
    <h2>Свободные переводчики</h2>
    <div class="cards-container">
        <div
        v-for="translator in freeTranslators"
        :key="translator.id"
        class="translator-card"
        >
        <div class="card-header">
            <h3>{{ translator.name }}</h3>
        </div>
        <div class="card-body">
            <p><strong>Availability:</strong> {{ translator.availability }}</p>
            <p><strong>ID:</strong> {{ translator.id }}</p>
        </div>
        <div class="card-footer">
            <select v-model="translator.newAvailability">
                <option value="weekday">Weekday</option>
                <option value="flexible">Flexible</option>
            </select>
            <button @click="setTranslatorAvailability(translator.id, translator.newAvailability)">
                Сменить режим работы
            </button>
        </div>
        </div>
    </div>
    <h2>Будние переводчики</h2>
    <div class="cards-container">
        <div
        v-for="translator in weekdayTranslators"
        :key="translator.id"
        class="translator-card"
        >
        <div class="card-header">
            <h3>{{ translator.name }}</h3>
        </div>
        <div class="card-body">
            <p><strong>Availability:</strong> {{ translator.availability }}</p>
            <p><strong>ID:</strong> {{ translator.id }}</p>
        </div>
        <div class="card-footer">
            <select v-model="translator.newAvailability">
                <option value="weekday">Weekday</option>
                <option value="flexible">Flexible</option>
            </select>
            <button @click="setTranslatorAvailability(translator.id, translator.newAvailability)">
                Сменить режим работы
            </button>
        </div>
        </div>
    </div>
    <h2>Ежедневные переводчики</h2>
    <div class="cards-container">
        <div
        v-for="translator in flexibleTranslators"
        :key="translator.id"
        class="translator-card"
        >
        <div class="card-header">
            <h3>{{ translator.name }}</h3>
        </div>
        <div class="card-body">
            <p><strong>Availability:</strong> {{ translator.availability }}</p>
            <p><strong>ID:</strong> {{ translator.id }}</p>
        </div>
        <div class="card-footer">
            <select v-model="translator.newAvailability">
                <option value="weekday">Weekday</option>
                <option value="flexible">Flexible</option>
            </select>
            <button @click="setTranslatorAvailability(translator.id, translator.newAvailability)">
                Сменить режим работы
            </button>
        </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
<script>
const { createApp, ref, onMounted } = Vue;

// const fetchTranslators = () => {
//     const payload = {
//         action: ""
//         availability: availability.value
//     };

//     fetch('/json/', {
//         method: 'POST', 
//         headers: {
//             'Content-Type': 'application/json'
//         },
//         body: JSON.stringify(payload)
//     })
//     .then(response => response.json())
//     .then(data => {
//         translators.value = data;
//     })
//     .catch(error => {
//         console.error('Ошибка запроса:', error);
//     });
// };


createApp({
    setup() {
        // const availability = ref('weekday');
        const freeTranslators = ref([]);
        const translators = ref([]);
        const weekdayTranslators = ref([]);
        const flexibleTranslators = ref([]);

        const getFreeTranslators = () => {
            fetch('/translatorsDistribution/translator/get-free-translators', {
                method: 'GET',
            })
            .then(response => response.json())
            .then(data => {
                freeTranslators.value = data;
            })
            .catch(error => console.error(error));
        };

        const getTranslators = () => {
            fetch('/translatorsDistribution/translator/get-translators', {
                method: 'GET',
            })
            .then(response => response.json())
            .then(data => {
                translators.value = data;
            })
            .catch(error => console.error(error));
        };

        const getFreeTranslatorByAvailability = (availability, targetArray) => {
            fetch('/translatorsDistribution/translator/get-free-translator-by-availability', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ availability })
            })
            .then(response => response.json())
            .then(data => {
                targetArray.value = data;
            })
            .catch(error => console.error(error));
        };

        const setTranslatorAvailability = (id, availability) => {
            fetch('/translatorsDistribution/translator/set-translator-availability', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ id, availability})
            })
            .then(response => response.json())
            .then(updatedTranslator  => {
                weekdayTranslators.value = weekdayTranslators.value.filter(t => t.id !== updatedTranslator.id);
                flexibleTranslators.value = flexibleTranslators.value.filter(t => t.id !== updatedTranslator.id);

                if (updatedTranslator.availability === 'weekday') {
                    weekdayTranslators.value.push(updatedTranslator);
                } else if (updatedTranslator.availability === 'flexible') {
                    flexibleTranslators.value.push(updatedTranslator);
                }
            })
            .catch(error => console.error(error));
        };
        
        onMounted(() => {
            getFreeTranslators();
            getTranslators();
            getFreeTranslatorByAvailability('weekday', weekdayTranslators);
            getFreeTranslatorByAvailability('flexible', flexibleTranslators);
        });

        return {
            freeTranslators,
            translators,
            setTranslatorAvailability,
            weekdayTranslators,
            flexibleTranslators
        };
    }
}).mount('#app');
</script>

<style>
.translator-list {
  padding: 20px;
}

.cards-container {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
  grid-gap: 20px;
}

.translator-card {
  background: #fff;
  border: 1px solid #e0e0e0;
  border-radius: 8px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
  display: flex;
  flex-direction: column;
  overflow: hidden;
}

.card-header {
  background-color: #f5f5f5;
  padding: 12px;
  border-bottom: 1px solid #ddd;
}

.card-header h3 {
  margin: 0;
  font-size: 1.1rem;
}

.card-body {
  padding: 12px;
  flex-grow: 1;
}

.card-body p {
  margin: 6px 0;
  font-size: 0.9rem;
}

.card-footer {
  padding: 10px 12px;
  background-color: #f9f9f9;
  border-top: 1px solid #ddd;
  text-align: right;
}

.card-footer button {
  padding: 6px 12px;
  font-size: 0.9rem;
  border: none;
  border-radius: 4px;
  background-color: #007bff;
  color: white;
  cursor: pointer;
}

.card-footer button:hover {
  background-color: #0056b3;
}
</style>