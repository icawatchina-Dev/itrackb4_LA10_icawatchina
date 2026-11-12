
H1
The file in `vendor` was where Laravel noticed the problem, not where I made it. The trace led me back to `BrgyController.php`, where I had typed `withh()` instead of `with()`.
 H2
My log records the barangay name when I save it. Later, I can check the log to see which barangay was added, something the error page would not tell me.

H3
I use a 403 when someone tries to delete the last barangay in a municipality, because that action is not allowed. I would redirect back with a message for a mistake the user can fix and try again.
