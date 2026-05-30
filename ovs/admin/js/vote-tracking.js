function updateVoteCounts() {
    fetch('get-vote-counts.php')
        .then(response => response.json())
        .then(data => {
            // Update total votes
            document.getElementById('totalVotes').textContent = data.totalVotes;
            
            // Calculate and update voter turnout
            const turnout = (data.totalVotes / data.totalVoters * 100).toFixed(1);
            document.getElementById('voterTurnout').textContent = turnout + '%';
            
            // Calculate votes per hour
            const votingRate = (data.totalVotes / data.hoursElapsed).toFixed(1);
            document.getElementById('votingRate').textContent = votingRate;
            
            // Update remaining voters
            document.getElementById('remainingVoters').textContent = 
                data.totalVoters - data.totalVotes;
            
            // Update each candidate's percentage
            data.candidates.forEach(candidate => {
                const percentage = (candidate.votes / data.totalVotes * 100).toFixed(1);
                const candidateElement = document.querySelector(
                    `[data-candidate-id="${candidate.id}"]`
                );
                
                if (candidateElement) {
                    const bar = candidateElement.querySelector('.percentage-bar');
                    const text = candidateElement.querySelector('.percentage-text');
                    const count = candidateElement.querySelector('.vote-count');
                    
                    bar.style.width = percentage + '%';
                    text.textContent = percentage + '%';
                    count.textContent = candidate.votes;
                }
            });
        });
}

// Update every 30 seconds
setInterval(updateVoteCounts, 30000);

// Initial update
updateVoteCounts();