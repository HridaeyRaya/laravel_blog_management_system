// Notes and CMDS

Admin check in tinker: User::whereHas('roles', fn($q) => $q->where('name', 'admin'))->first()
ryan26@example.com

User check in tinker: User::whereHas('roles', fn($q) => $q->where('name', 'user'))->first()


