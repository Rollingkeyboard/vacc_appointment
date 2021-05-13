import math
import json
import sys

class Scheduler:
    def __init__(self, testdata=None):
        self.providers = {}
        self.providers_location = {}
        self.users = {}
        self.users_max_dist = {}
        self.users_location = {}
        self.result = {}
        if testdata is None:
            self.read_pat()
            self.read_ppt()

    def assign(self, users=None, providers=None):
        """
        Assign appointment from provider_available_time and patient_preferred_time
        """
        if users is None:
            users = self.users
        if providers is None:
            providers = self.providers
        # {time1: [user1, user2, ...], ...}
        time_reg = {time: [] for time in range(1, 22)}
        time_count = {time: 0 for time in range(1, 22)}
        # {user1:count1, user2:count2, ...}
        pref_count = {user: len(users[user]) for user in users}

        # initiate time_reg
        for u in users:
            for t in users[u]:
                time_reg[t].append(u)
        # initiate time_count
        for p in providers:
            for t in providers[p]:
                time_count[t[1]] += 1

        for _ in range(len(pref_count)):
            # find a user with the least preferences/choices
            user = min(pref_count, key=pref_count.get)
            count = pref_count[user]
            pref_count[user] = math.inf
            if count > 0:
                # find a time slot
                i = 0
                while i < len(users[user]) and time_count[users[user][i]] == 0:
                    i += 1
                # skip this user if failed to find one
                if i == len(users[user]):
                    continue
                timeslot = users[user][i]

                # find a provider
                pat_id = -1
                for p in providers:
                    i = 0
                    # check this provider's available time
                    while i < len(providers[p]):
                        if providers[p][i][1] == timeslot and self.arrivable(user, p):
                            provider = p
                            pat_id = providers[p][i][0]
                            break
                        i += 1
                    # found one
                    if i < len(providers[p]):
                        break

                # failed to find one
                if pat_id == -1:
                    continue

                # update time_count
                time_count[timeslot] -= 1

                # update pref_count optionally
                if time_count[timeslot] == 0:
                    for u in time_reg[timeslot]:
                        pref_count[u] -= 1

                w_id = (timeslot - 1) // 3 + 1
                t_id = (timeslot - 1) % 3 + 1
                self.result[str(user)] = {
                    "pat_id": pat_id,
                    "provider_id": provider,
                    "w_id": w_id,
                    "t_id": t_id
                }

            self.output_file()

    def arrivable(self, user, provider):
        """
        determine if the provider is within the user's expected distance
        """
        if self.haversine(self.users_location[user][0],
                          self.users_location[user][1],
                          self.providers_location[provider][0],
                          self.providers_location[provider][1]) <= self.users_max_dist[user]:
            return True
        return False
            

    def haversine(self, long1, lat1, long2, lat2):
        """
        haversine formula
        calculate geographical distance from longitudes and latitudes
        """
        radius = 3959.87433      # Earch radius in miles (6372.8 in kilometer)
        delta_lat = math.radians(lat2 - lat1)
        delta_long = math.radians(long2 - long1)
        lat1, lat2 = math.radians(lat1), math.radians(lat2)

        temp = math.sin(delta_lat / 2) ** 2 + math.cos(lat1) * math.cos(lat2) * math.sin(delta_long / 2) ** 2
        return radius * 2 * math.asin(math.sqrt(temp))

    def output_file(self):
        """
        output the result to appointment.json
        """
        print(self.result)
        with open("appointment.json", "w") as appfile:
            appfile.write(json.dumps(self.result, indent=4))

    # patient_preferred_time
    def read_ppt(self):
        """
        turn list of
        {
            "ppt_id": "<ppt_id>",
            "patient_id": "<patient_id>",
            "w_id": "<w_id>",
            "t_id": "<t_id>"
        }
        into dict of
            user: [timeslot]
        """
        with open("ppt_rows.json") as ppt_file:
            ppt_data = json.load(ppt_file)
        for ppt in ppt_data:
            patient_id = int(ppt['patient_id'])
            w_id = int(ppt['w_id'])
            t_id = int(ppt['t_id'])
            patient_longitude = float(ppt['patient_longitude'])
            patient_latitude = float(ppt['patient_latitude'])
            max_distance = float(ppt['max_distance'])
            if patient_id not in self.users:
                self.users[patient_id] = []
            self.users[patient_id].append(3 * (w_id - 1) + t_id)
            self.users_location[patient_id] = (patient_longitude, patient_latitude)
            self.users_max_dist[patient_id] = max_distance

    # provider_available_time
    def read_pat(self):
        """
        turn list of
        {
            "pat_id": "<pat_id>",
            "provider_id": "<provider_id>",
            "w_id": "<w_id>",
            "t_id": "<t_id>"
        }
        into dict of
            provider: [(pat_id, timeslot)]
            timeslot ranges from 1 to 21 inclusively
        """
        with open("pat_rows.json") as pat_file:
            pat_data = json.load(pat_file)
        for pat in pat_data:
            pat_id = int(pat['pat_id'])
            provider_id = int(pat['provider_id'])
            w_id = int(pat['w_id'])
            t_id = int(pat['t_id'])
            provider_latitude = float(pat['provider_latitude'])
            provider_longitude = float(pat['provider_longitude'])
            if provider_id not in self.providers:
                self.providers[provider_id] = []
            self.providers[provider_id].append((pat_id, 3 * (w_id - 1) + t_id))
            self.providers_location[provider_id] = (provider_longitude, provider_latitude)

if __name__ == '__main__':
    if len(sys.argv) > 1 and sys.argv[1] == 'test':
        test_users = {
            12: [1, 3, 4],
            11: [1],
            3: [3, 5],
            7: [4]
        }
        test_providers = {
            1: [(1, 1)],
            3: [(2, 3)],
            4: [(3, 4)],
            5: [(4, 5)]
        }
        scheduler = Scheduler(testdata=True)
        # {11: 1, 7: 4, 12: 3, 3: 5}
        scheduler.assign(test_users, test_providers)
    if len(sys.argv) == 1:
        print("not testing\n")
        scheduler = Scheduler()
        scheduler.assign()
