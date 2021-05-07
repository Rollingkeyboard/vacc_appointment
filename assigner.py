import math

def assign(users: dict, providers: dict):
    # {time1: [user1, user2, ...], ...}
    timeReg = {time: [] for time in range(1, 22)}
    timeCount = {time: 0 for time in range(1, 22)}
    # {user1:count1, user2:count2, ...}
    prefCount = {user: len(users[user]) for user in users}
    
    # initiate timeReg
    for u in users:
        for t in users[u]:
            timeReg[t].append(u)
    # initiate timeCount
    for p in providers:
        for t in providers[p]:
            timeCount[t] += 1
    
    out = {}
    for _ in range(len(prefCount)):
        # find a user with the least preferences/choices
        user = min(prefCount, key = prefCount.get)
        prefCount[user] = math.inf
        if prefCount[user] > 0:
            i = 0
            # find a time slot
            while timeCount[users[user][i]] == 0:
                i += 1
            timeslot = users[user][i]
            # find a provider
            for p in providers:
                if timeslot in providers[p]:
                    provider = p
            # update timeCount
            timeCount[timeslot] -= 1
            # update prefCount optionally
            if timeCount[timeslot] == 0:
                for u in timeReg[timeslot]:
                    prefCount[u] -= 1
            out[str(user)] = {"provider": provider, "timeslot": timeslot}
    return out

users = {
    1: [1,3,4],
    2: [1],
    3: [3, 5],
    4: [4]
}
providers = {
    1: [1],
    3: [3],
    4: [4],
    5: [5]
}
# {1:3, 2:1, 3:5, 4:4}
print(assign(users, providers))
