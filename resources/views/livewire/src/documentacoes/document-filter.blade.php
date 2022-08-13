<select class="form-select" id="docFilter">
    <option>Tipo de documentação</option>
    <option value = "dailyScrum">Daily Scrum</option>
    <option value = "sprintPlan">Sprint Planning</option>
    <option value = "sprintRetro">Sprint Retrospective</option>
    <option value = "sprintReview">Sprint Review</option>
</select>

<script>
    var option = document.getElementById('docFilter').value;
    document.addEventListener('livewire:load', function() {
    });
</script>