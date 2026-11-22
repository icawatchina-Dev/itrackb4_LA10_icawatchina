## la12
H1
The file in `vendor` was where Laravel noticed the problem, not where I made it. The trace led me back to `BrgyController.php`, where I had typed `withh()` instead of `with()`.

H2
My log records the barangay name when I save it. Later, I can check the log to see which barangay was added, something the error page would not tell me.

H3
I use a 403 when someone tries to delete the last barangay in a municipality, because that action is not allowed. I would redirect back with a message for a mistake the user can fix and try again.


## lab 13 Part J

J1. My home page only counts barangays and municipalities. It doesn’t need to list and paginate every barangay.

J2. Before, the pagination link only had the page number, so the search was lost when I moved to another page. I fixed it with withQueryString().

J3. A wrong sort value silently goes back to the default, so I might not notice the typo. The allowed list only accepts brgy_name and population; anything else uses the default.

Btw po sir from  20 to 45 po kasi in-update kopo ung sa ibang brgy kaya naging 45 po lahat

## lab 10Part G

G1. I made a `municipalities` table from the city names because many barangays can share one municipality. I kept population with each barangay.

G2. Screenshot 6 showed **7 queries**: one for the barangay list and one for each of the 6 municipalities loaded separately.

G3. I used `municipality` because one barangay belongs to one municipality. If I made it plural, `with('municipality')` would not find it.

## Lab 11 - Part G

G1. I need to type-hint the `Barangay` model and name the parameter `$brgy` to match the `{brgy}` route. If either one is wrong, Laravel will not bind the record correctly.

G2. A link sends a GET request, but the delete route needs a DELETE request. So the browser opens the detail page instead of deleting the barangay.

G3. Without the second argument, the edit fields are blank the first time I open the form. If I use only the saved value and not `old()`, my typed values disappear after a validation error.

## Part F - Authentication and Authorization

F1
This lesson taught me how users can register, log in, and log out, and how to stop guests from changing barangays. Next meeting, I still need to learn how to control what each signed-in user can do.

F2
After a user logs in, the app changes the session ID to help protect the account. If it did not, someone might reuse the old session ID to access the signed-in account.

F3
My login form shows the same error for an unknown email and a wrong password. This makes it harder for strangers to find out which email addresses have accounts.

F4
Anyone can view the barangay list and details without an account. Users must log in to add, edit, update, or delete barangays because those actions change the data.


Hiding the Add, Edit, and Delete buttons only changes what guests see on the page. The `auth` middleware protects the routes and sends guests to the login page if they try to use them.

Laravel uses the route named `login` to send guests to the login page. If I rename it, Laravel cannot find it and shows the `Route [login] not defined` error.
