Admin check in tinker: User::whereHas('roles', fn($q) => $q->where('name', 'admin'))->first()

Email: fratke@example.com
Pass: password

User check in tinker: User::whereHas('roles', fn($q) => $q->where('name', 'user'))->first()

email: kaela83@example.org
pass: password
