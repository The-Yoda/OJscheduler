package com.example.ojscheluder;

import java.io.File;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.Map;

import android.app.Activity;
import android.os.Bundle;
import android.os.Environment;
import android.util.Log;
import android.view.Menu;

public class MainActivity extends Activity {

	public static final String DATA_PATH = Environment
			.getExternalStorageDirectory().toString() + "/OJScheduler/";
	
	public static final String baseURL = "http://192.168.43.236/";

	private static final String TAG = "Scheduler.java";

	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		// Log.e("time", TimeZone.getDefault().toString());
		File dir = new File(DATA_PATH);
		if (!dir.exists()) {
			if (!dir.mkdirs()) {
				Log.v(TAG, "ERROR: Creation of directory " + DATA_PATH
						+ " on sdcard failed");
				return;
			} else {
				Log.v(TAG, "dir" + DATA_PATH + "already exists");
			}
		}
		ArrayList<String> contestNames = new ArrayList<String>();
		contestNames.add("srm");
		Map<String, ArrayList<String>> site = new HashMap<String, ArrayList<String>>();
		site.put("Topcoder", contestNames);
		ContestManager cManager = new ContestManager();
		cManager.updateSchedule((HashMap<String, ArrayList<String>>) site, baseURL, DATA_PATH);
		setContentView(R.layout.activity_main);
	}

	@Override
	public boolean onCreateOptionsMenu(Menu menu) {
		// Inflate the menu; this adds items to the action bar if it is present.
		getMenuInflater().inflate(R.menu.main, menu);
		return true;
	}
}
