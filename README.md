G1. I made municipality its own table because many barangays share the same municipality. Population stays with each barangay because every barangay can have a different population.

G2. Screenshot 6 showed 7 queries: one to get the barangays, then six more to look up each barangay’s municipality. With with('municipality'), the same page needs only 2.

G3. Each barangay has one municipality, so the relationship is called municipality. If I changed it to municipalities, I’d also need to change the code that uses $brgy->municipality->name. Otherwise, the page would break.sta